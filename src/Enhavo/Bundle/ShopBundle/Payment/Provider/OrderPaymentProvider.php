<?php

namespace Enhavo\Bundle\ShopBundle\Payment\Provider;

use Enhavo\Bundle\ShopBundle\Exception\NotProvidedOrderPaymentException;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use SM\Factory\FactoryInterface as StateMachineFactoryInterface;
use Sylius\Component\Payment\Exception\UnresolvedDefaultPaymentMethodException;
use Sylius\Component\Payment\Factory\PaymentFactoryInterface;
use Sylius\Component\Payment\Model\PaymentInterface;
use Sylius\Component\Payment\Model\PaymentMethodInterface;
use Sylius\Component\Payment\PaymentTransitions;
use Sylius\Component\Payment\Resolver\DefaultPaymentMethodResolverInterface;
use Sylius\Component\Resource\StateMachine\StateMachineInterface;

class OrderPaymentProvider implements OrderPaymentProviderInterface
{
    public function __construct(
        private DefaultPaymentMethodResolverInterface $defaultPaymentMethodResolver,
        private PaymentFactoryInterface $paymentFactory,
        private StateMachineFactoryInterface $stateMachineFactory,
    ) {}

    public function provideOrderPayment(OrderInterface $order, string $targetState): ?PaymentInterface
    {
        /** @var PaymentInterface $payment */
        $payment = $this->paymentFactory->createWithAmountAndCurrencyCode($order->getTotal(), $order->getCurrencyCode());

        $paymentMethod = $this->getDefaultPaymentMethod($payment, $order);
        $lastPayment = $this->getLastPayment($order);

        if (null !== $lastPayment) {
            $paymentMethod = $lastPayment->getMethod();
        }

        if (null === $paymentMethod) {
            throw new NotProvidedOrderPaymentException();
        }

        $payment->setMethod($paymentMethod);
        $this->applyRequiredTransition($payment, $targetState);

        return $payment;
    }

    private function getLastPayment(OrderInterface $order): ?PaymentInterface
    {
        $lastCancelledPayment = $order->getLastPayment(PaymentInterface::STATE_CANCELLED);
        if (null !== $lastCancelledPayment) {
            return $lastCancelledPayment;
        }

        return $order->getLastPayment(PaymentInterface::STATE_FAILED);
    }

    private function getDefaultPaymentMethod(PaymentInterface $payment, OrderInterface $order): ?PaymentMethodInterface
    {
        try {
            $payment->setOrder($order);

            return $this->defaultPaymentMethodResolver->getDefaultPaymentMethod($payment);
        } catch (UnresolvedDefaultPaymentMethodException $exception) {
            return null;
        }
    }

    private function applyRequiredTransition(PaymentInterface $payment, string $targetState): void
    {
        if ($targetState === $payment->getState()) {
            return;
        }

        /** @var StateMachineInterface $stateMachine */
        $stateMachine = $this->stateMachineFactory->get($payment, PaymentTransitions::GRAPH);

        $targetTransition = $stateMachine->getTransitionToState($targetState);
        if (null !== $targetTransition) {
            $stateMachine->apply($targetTransition);
        }
    }
}
