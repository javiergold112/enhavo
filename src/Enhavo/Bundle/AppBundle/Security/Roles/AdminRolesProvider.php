<?php
/**
 * AdminRolesProvider.php
 *
 * @since 24/10/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\AppBundle\Security\Roles;

class AdminRolesProvider implements RolesProvider
{
    /**
     * @var array
     */
    protected $roles = array();

    public function __construct($roles)
    {
        foreach($roles as $role => $value) {
            if(preg_match('/enhavo/i', $role)) {
                $this->roles[$role] = $value;
            }
        }
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }
}