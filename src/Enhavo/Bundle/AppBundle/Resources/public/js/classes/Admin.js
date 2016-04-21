function Admin (router, templating, translator)
{
  var self = this;
  var overlay = $("#overlay");
  var overlayContent = $("#overlayContent");
  var overlayMessage = $("#overlayMessage");
  var ajaxOverlaySynchornized = true;

  var MessageType = {
    Info: 'info',
    Error: 'error',
    Success: 'success'
  };

  /**
   * overlay
   *
   * html
   *
   * option have this keys
   * options = {
  *   init: function
  * };
   *
   * @param html
   * @param options
   * @returns void
   */
  this.overlay = function(html, options) {

    if(!options) {
      options = {};
    }

    var init = function() {

      $(document).one('keyup',function(event) {
        if(event.keyCode == 27) {
          event.stopPropagation();
          event.preventDefault();
          self.overlayClose();
        }
      });

      overlayContent.find('.close').click(function(event) {
        event.stopPropagation();
        event.preventDefault();
        self.overlayClose();
      });

      self.initTabs('#overlayContent');
    };

    var overlayStart = function() {
      overlayContent.trigger('formOpenBefore');

      overlay.fadeIn(50);
      overlayContent.append($.parseHTML(html));
      overlayContent.fadeIn(100, function() {
        overlayContent.trigger('formOpenAfter', [ overlayContent.find('form').get(0) ]);
      });
      overlayContent.animate({
        scrollTop: 0
      }, 100);

      init();
      if(options.init) {
        options.init();
      }
    };

    overlayStart();
  };

  this.overlayClose = function()
  {
    var overlayStop = function() {
      overlayContent.trigger('formCloseBefore', [overlayContent]);
      overlayContent.html('');
      overlay.fadeOut(100);
      overlayContent.fadeOut(50);
      overlayContent.trigger('formCloseAfter', [overlayContent]);
    };
    overlayStop();
  };

  /**
   * ajaxOverlay
   *
   * html
   *
   * option have this keys
   * options = {
   *   init: function
   * };
   *
   * @param url
   * @param options
   * @returns void
   */
  this.ajaxOverlay = function(url, options)
  {
    if(ajaxOverlaySynchornized) {
      ajaxOverlaySynchornized = false;
      $.ajax({
        url: url,
        success : function(data) {
          self.overlay(data, options);
          ajaxOverlaySynchornized = true;
        },
        error : function(data) {
          var message = 'error.occurred';
          if(data.status == 403) {
            message = 'error.forbidden';
          }
          self.overlayMessage(translator.trans(message), MessageType.Error);
          ajaxOverlaySynchornized = true;
        }
      });
    }

  };

  /**
   *
   * option have this keys
   * options = {
   *   submit: boolean If true the form will be submitted to the iframe overlay
   * };
   *
   * @param form
   * @param url
   * @param options
   */
  this.iframeOverlay = function(form,url,options)
  {
    if(!options) {
      options = {};
    }

    var originalAction = $(form).prop('action');
    var iframeContainer = $('#iframeContainer');
    var iframe = iframeContainer.find('iframe');

    if(options.submit) {
      $(form).prop('target','iframe');
      $(form).prop('action',url);
      $(form).submit();
    }

    iframeContainer.fadeIn(100);
    iframeContainer.find('.close').on('click',function(event) {
      event.stopPropagation();
      event.preventDefault();
      self.iframeClose(form,originalAction);
    });
  };

  this.iframeClose = function(form,originalAction) {
    var iframeContainer = $('#iframeContainer');
    var iframe = iframeContainer.find('iframe');
    iframeContainer.fadeOut(200,function() {
      iframe.prop('src','');
      $(form).prop('target','');
      $(form).prop('action',originalAction);
    });
  };

  this.overlayMessage = function(content, type)
  {
    var overlayTimeout = null;
    clearTimeout(overlayTimeout);
    if(!type) {
      type = MessageType.Info;
    }

    overlayMessage.removeClass(MessageType.Info);
    overlayMessage.removeClass(MessageType.Error);
    overlayMessage.removeClass(MessageType.Success);
    overlayMessage.addClass(type);

    overlayMessage.html(content).stop().fadeIn(150,function() {
      overlayTimeout = setTimeout(function() {
        overlayMessage.fadeOut(150);
      }, 3500);
    });
  };

  this.initPagination = function(route, selector)
  {
      /*
    $(document).on('click', '[data-pagination] [data-page]', function() {
      console.log(this);
      var block = $(this).parents('[data-block]');
      console.log(block);
      var page = $(this).data('page');
      var link = router.generate(route, { page: page });
      $('a',pagination).removeClass('selected');
      $('a[page='+page+']',pagination).addClass('selected');
      self.updateTable(referingTableContainer,link);
    });
    */
  };

  this.initTable = function() {
    $(document).on('click', '.table-container .entry-row', function() {
      var tableContainer = $(this).parents('.table-container');
      var route = tableContainer.attr('data-edit-route');
      var id = $(this).attr('data-id');
      var url = router.generate(route, { id: id });
      self.ajaxOverlay(url);
    });
  };

  this.updateTable = function(tableContainer,url, callback)
  {
    if(!url) {
      url = router.generate(tableContainer.attr('data-refresh-route'));
    }

    $.ajax({
      url: url,
      success : function(data) {
        tableContainer.html(data);
        if(callback) {
          callback();
        }
      },
      error : function() {
        self.overlayMessage(translator.trans('error.occurred') , MessageType.Error);
      }
    })
  };

  this.initActions = function() {
    $(document).on('click', '[data-action]' , function(event) {
      event.stopPropagation();
      event.preventDefault();
      var route = $(this).data('action-route');
      var link = router.generate(route);
      self.ajaxOverlay(link);
    });
  };

  this.initTabs = function(selector)
  {
    $(selector + " .tabContainer a").each(function() {
      var tab = $("#"+$(this).attr("tabId"));
      if(tab.length > 0) {
        tab.hide();
        $(this).on("click",function(e) {
          e.preventDefault();
          $(this).siblings("a").each(function() {
            var toHide = $("#"+$(this).attr("tabId"));
            toHide.hide();
            $(this).removeClass("selected");
          });
          $(this).addClass("selected");
          tab.show();
        });
      }
    });
    $(selector + " .tabContainer a:first-child").trigger("click");
  };

  this.initAfterSaveHandler = function()
  {
      $(document).on('click', '[data-button][data-type=cancel]', function() {
          self.overlayClose();
      });
  };

  this.initBlocks = function()
  {
    $('[data-block]').each(function(index, element) {
        var type = $(this).data('block-type');
        var route = $(element).data('block-table-route');
        var url = router.generate(route);
        var block = $(this);
        $.get(url, function (data) {
            block.html(data);

            $(document).on('formSaveAfter', function() {
                var page = block.data('block-page');
                var url = router.generate(route, {page: page});
                $.get(url, function (data) {
                    block.html(data);
                });
            });

            block.on('click', '[data-page]', function() {
               var page = $(this).data('page');
               block.data('block-page', page);
               var url = router.generate(route, {page: page});
                $.get(url, function (data) {
                    block.html(data);
                });
            });

            block.on('click', '[data-id]', function() {
                var id = $(this).data('id');
                var route = block.data('block-update-route');
                var url = router.generate(route, {id: id});
                self.ajaxOverlay(url);
            });
        })
    });
  };

  this.initNavigation = function()
  {
    $(function(){
      $('[data-mobile-menu]').on('click', function(){
        $('[data-menu-container]').toggleClass("active");
        $('[data-content-container]').toggleClass("push");
        $(this).toggleClass("push");
      });
    });
  }

  this.initDescriptionTextPosition = function()
  {
    $(window).on("load resize",function() {
      var desc = $("[data-description-text]");
      var wh = $(window).height()
      var menuHeight = $("#menu-main").height() + 240;

      if(menuHeight > wh) {
        $(desc).css({"position" : "relative", "bottom" : "15px"})
      } else
      {
        $(desc).css({"position" : "absolute", "bottom" : "25px"})
      }
    });
  }

}

