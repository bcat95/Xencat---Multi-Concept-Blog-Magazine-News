var BCxencat = window.BCxencat || {};

!function($, window, document)
{
	// ################################## --- ###########################################
	/*
	BCxencat.Masonry = XF.Element.newHandler(
	{
		init: function()
		{
			var $grid = this.$target.masonry(
			{
				itemSelector: '.porta-article-item',
			});
			
			$grid.imagesLoaded().progress( function()
			{
				$grid.masonry('layout');
			});
		},
	});
	*/
	/* Masonry layout
	 ----------------------------------------------------------------*/
	BCxencat.Masonry = XF.Element.newHandler(
	{
		init: function()
		{
			
			$(window).on('load', function() {
				var $masonry_container = $( '.penci-masonry' );
				if ( $masonry_container.length ) {
					$masonry_container.each( function () {
						var $this = $( this );
						// initialize isotope
						$this.isotope( {
							itemSelector      : '.item-masonry',
							transitionDuration: '.55s',
							layoutMode        : 'masonry'
						} );
					} );
				}
			})
			
		},
	});
	
	function masonryInfinite (){
		if ( $().masonry ) {
			var masonryOptions = {
				itemSelector: '.item-masonry',
			};
			// initialize Masonry
			var $grid = $('.penci-masonry').masonry( masonryOptions );
			$grid.masonry('destroy');
			$grid.masonry( masonryOptions );
		}
	}
	
	
	/* Homepage Featured Slider
	 ---------------------------------------------------------------*/
	 
	BCxencat.featured_slider = XF.Element.newHandler(
	{
		init: function()
		{
			if ( $().owlCarousel ) {
				$( '.featured-area .penci-owl-featured-area' ).each( function () {
					var $this = $( this ),
						$style = $this.data( 'style' ),
						$auto = false,
						$autotime = $this.data( 'autotime' ),
						$speed = $this.data( 'speed' ),
						$loop = $this.data('loop'),
						$item = 1,
						$nav = true,
						$dots = false,
						$rtl = false,
						$items_desktop = 1,
						$items_tablet = 1,
						$items_tabsmall = 1;

					if( $style === 'style-2' ) {
						$item = 2;
					} else if( $style === 'style-28' ) {
						$loop = true;
					}

					if( $('html').attr('dir') === 'rtl' ) {
						$rtl = true;
					}
					if ( $this.attr('data-auto') === 'true' ) {
						$auto = true;
					}
					if ( $this.attr('data-nav') === 'false' ) {
						$nav = false;
					}
					if ( $this.attr('data-dots') === 'true' ) {
						$dots = true;
					}
					if ( $this.attr('data-item') ) {
						$item = parseInt( $this.data('item') );
					}
					if ( $this.attr('data-desktop') ) {
						$items_desktop = parseInt( $this.data('desktop') );
					}
					if ( $this.attr('data-tablet') ) {
						$items_tablet = parseInt( $this.data('tablet') );
					}
					if ( $this.attr('data-tabsmall') ) {
						$items_tabsmall = parseInt( $this.data('tabsmall') );
					}

					var owl_args = {
						rtl               : $rtl,
						loop              : $loop,
						margin            : 0,
						items             : $item,
						navSpeed          : $speed,
						dotsSpeed         : $speed,
						nav               : $nav,
						slideBy           : $item,
						mouseDrag         : false,
						lazyLoad          : true,
						dots              : $dots,
						navText           : ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
						autoplay          : $auto,
						autoplayTimeout   : $autotime,
						autoplayHoverPause: true,
						autoplaySpeed     : $speed,
						responsive        : {
							0   : {
								items: 1
							},
							480 : {
								items  : $items_tabsmall,
								slideBy: $items_tabsmall
							},
							768 : {
								items  : $items_tablet,
								slideBy: $items_tablet
							},
							1170: {
								items  : $items_desktop,
								slideBy: $items_desktop
							}
						}
					}

					if( $style === 'style-2' ) {
						owl_args['center'] = true;
						owl_args['margin'] = 10;
						owl_args['autoWidth'] = true;
					} else if( $style === 'style-28' ) {
						owl_args['margin'] = 4;
						owl_args['items'] = 6;
						owl_args['autoWidth'] = true;
					}

					$this.owlCarousel( owl_args );

					if( $style === 'style-2' || $style === 'style-5' || $style === 'style-28' || $style === 'style-29' ) {
						$this.on( 'changed.owl.carousel', function ( event ) {
							$this.find( '.penci-lazy' ).Lazy( {
								effect: 'fadeIn',
								effectTime: 300,
								scrollDirection: 'both'
							} );
						} );
					}
				} );
			}	// if owlcarousel
		},
	});

	
	/* Owl Slider General
	 ---------------------------------------------------------------*/
	
	BCxencat.owl_slider = XF.Element.newHandler(
	{
		init: function()
		{
			if ( $().owlCarousel ) {
			$( '.penci-owl-carousel-slider' ).each( function () {
				var $this = $( this ),
					$auto = true,
					$dots = false,
					$nav = true,
					$loop = true,
					$rtl = false,
					$dataauto = $this.data( 'auto' ),
					$items_desktop = 1,
					$items_tablet = 1,
					$items_tabsmall = 1,
					$speed = 600,
					$item = 1,
					$autotime = 5000,
					$datalazy = false;

				if( $('html').attr('dir') === 'rtl' ) {
					$rtl = true;
				}
				if ( $this.attr('data-dots') ) {
					$dots = true;
				}
				if ( $this.attr('data-loop') ) {
					$loop = false;
				}
				if ( $this.attr('data-nav') ) {
					$nav = false;
				}
				if ( $this.attr('data-desktop') ) {
					$items_desktop = parseInt( $this.data('desktop') );
				}
				if ( $this.attr('data-tablet') ) {
					$items_tablet = parseInt( $this.data('tablet') );
				}
				if ( $this.attr('data-tabsmall') ) {
					$items_tabsmall = parseInt( $this.data('tabsmall') );
				}
				if ( $this.attr('data-speed') ) {
					$speed = parseInt( $this.data('speed') );
				}
				if ( $this.attr('data-autotime') ) {
					$autotime = parseInt( $this.data('autotime') );
				}
				if ( $this.attr('data-item') ) {
					$item = parseInt( $this.data('item') );
				}
				if ( $this.attr('data-lazy') ) {
					$datalazy = true;
				}

				var owl_args = {
					loop              : $loop,
					rtl               : $rtl,
					margin            : 0,
					items             : $item,
					slideBy           : $item,
					lazyLoad          : $datalazy,
					navSpeed          : $speed,
					dotsSpeed         : $speed,
					nav               : $nav,
					dots              : $dots,
					navText           : ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
					autoplay          : $dataauto,
					autoplayTimeout   : $autotime,
					autoHeight        : true,
					autoplayHoverPause: true,
					autoplaySpeed     : $speed,
					responsive        : {
						0   : {
							items  : 1,
							slideBy: 1
						},
						480 : {
							items  : $items_tabsmall,
							slideBy: $items_tabsmall
						},
						768 : {
							items  : $items_tablet,
							slideBy: $items_tablet
						},
						1170: {
							items  : $items_desktop,
							slideBy: $items_desktop
						}
					}
				};

				if ( $this.hasClass( 'penci-headline-posts' ) ) {
					owl_args['animateOut'] = 'slideOutUp';
					owl_args['animateIn'] = 'slideInUp';
				}

				$this.owlCarousel( owl_args );

				$this.on('changed.owl.carousel', function(event) {
					$this.find( '.penci-lazy' ).Lazy( {
						effect: 'fadeIn',
						effectTime: 300,
						scrollDirection: 'both'
					} );
				});
			} );
		}	// if owlcarousel
		},
	});
	
	
	
	
	
	BCxencat.Infinite = XF.Element.newHandler(
	{
		init: function()
		{
			$grid = this.$target;
			
			var $scroller = $grid.infiniteScroll({
				outlayer: $grid.data('masonry'),
				button: '.xencat-article-button',
				append: '.xencat-article-item',
				hideNav: '.xencat-article-pager',
				path: '.xencat-article-pager .pageNav-jump--next',
				status: '.xencat-article-status',
			}
			);
			
			$scroller.on('last.infiniteScroll', function()
			{
				$('.xencat-article-status').hide();
				$('.xencat-article-loader').hide();
			});
			
			if ($grid.data('click'))
			{
				if ($grid.data('after'))
				{
					$scroller.on('load.infiniteScroll', function onPageLoad()
					{
						console.log('load.infiniteScroll');
						if ($scroller.data('infiniteScroll').loadCount == $grid.data('after'))
						{
							$('.xencat-article-loader').show();
							$scroller.infiniteScroll('option', { loadOnScroll: false });
							$scroller.off('load.infiniteScroll', onPageLoad);
						}
					});
				}
				else
				{
					$('.xencat-article-loader').show();
					$scroller.infiniteScroll('option', { loadOnScroll: false });
				}
			}
			
			$scroller.on( 'append.infiniteScroll', function( event, response, path, items ) {
				//$(".xencat-owl-carousel").trigger('destroy.owl.carousel');
				$(".penci-owl-carousel-slider").owlCarousel({
					items:1,
					nav:true,
					dot:false,
					navText: ['<i class="fa fa-angle-left"></i>','<i class="fa fa-angle-right"></i>']
				});
				masonryInfinite();
			});
			
		},
	});
	
	// ################################## --- ###########################################
	
	/*
	BCxencat.Features = XF.Element.newHandler(
	{
		init: function()
		{
			var $slider = this.$target,
				players = [],
				ytDeferred = $.Deferred();
				
			if ($slider.data('relocate'))
			{
				$slider.detach().insertBefore($slider.data('relocate'));
			}
			
			$.getScript('https://www.youtube.com/player_api');
			window.onYouTubeIframeAPIReady = function()
			{
				ytDeferred.resolve(window.YT);
			};
	
			ytDeferred.done(function()
			{
				$slider.find('.porta-features-media').each(function()
				{
					var slide = $(this).data('slide');
					var index = 'youTube' + slide;
					var $elem = $slider.find('.feature-' + slide);
					
					$elem.addClass('has-media');
					
					players[index] = new YT.Player(index,
					{
						videoId: $(this).data('media'),
						width: "100%",
						height: "100%",
						playerVars:
						{
							playlist: $(this).data('media'),
							enablejsapi: 1,
							autoplay: 0,
							controls: 0,
							showinfo: 0,
							disablekb: 1,
							modestbranding: 1,
							cc_load_policy: 0,
							iv_load_policy: 3,
							loop: 1,
							rel: 0,
							fs: 0
						},
						events:
						{
							onReady: function(e)
							{
								e.target.mute();
								
								$elem.find('.bx-unmute').click(function(v)
								{
									v.preventDefault();
									e.target.unMute();
									bxSlider.stopAuto();
								
									$elem.find('.bx-unmute').addClass('active');
									$elem.find('.bx-mute').removeClass('active');
								});
								$elem.find('.bx-mute').click(function(v)
								{
									v.preventDefault();
									e.target.mute();
									
									$elem.find('.bx-mute').addClass('active');
									$elem.find('.bx-unmute').removeClass('active');
								});
							}
						}
					});
				});
			});
			
			var bxProgressStart = function()
			{
				$slider.find('.bx-progress').stop().css({ width: '0%' }).animate({ width: '100%' },
				{
					duration: ($slider.data('auto') - ($slider.data('speed') / 2)),
					easing: 'linear'
				});
			}
			var bxProgressStop = function()
			{
				$slider.find('.bx-progress').stop().animate({ width: '0%' },
				{
					duration: ($slider.data('speed') / 2),
					easing: 'linear',
					complete: function ()
					{
						$slider.find('.bx-progress').hide();
					}
				});
			}
			var bxProgressShow = function()
			{
				$slider.find('.bx-progress').show();
				bxProgressStart();
			}
			
			var bxSlider = $slider.find('.porta-features-container').bxSlider(
			{
				autoHover: true,
				autoDelay: 4000,
				auto: $slider.data('auto'),
				mode: $slider.data('mode'),
				speed: $slider.data('speed'),
				pause: $slider.data('auto'),
				pager: $slider.data('pager') ? 'true' : 'false',
				controls: $slider.data('controls') ? 'true' : 'false',
				autoControls: $slider.data('autoControls') ? 'true' : 'false',
				onAutoChange: function(auto)
				{
					auto ? bxProgressShow() : bxProgressStop();
				},
				onSlideBefore: function(slide, oldIndex, newIndex)
				{
					newPlayer = 'youTube' + (newIndex + 1);
					if (newPlayer in players)
					{
						players[newPlayer].playVideo();
					}
					bxProgressStart();
				},
				onSlideAfter: function(slide, oldIndex, newIndex)
				{
					oldPlayer = 'youTube' + (oldIndex + 1);
					if (oldPlayer in players)
					{
						players[oldPlayer].stopVideo();
					}
				},
				onSliderLoad: function(newIndex)
				{
					setTimeout(function()
					{
						newPlayer = "youTube" + (newIndex + 1);
						if (newPlayer in players)
						{
							players[newPlayer].playVideo();
						}
					}, 4000);
				},
			});
		},
	});
	*/
	// ################################## --- ###########################################

	//XF.Element.register('porta-masonry', 'BCxencat.Masonry');
	XF.Element.register('xencat-masonry', 'BCxencat.Masonry'); 
	XF.Element.register('xencat-featured-slider', 'BCxencat.featured_slider');
	XF.Element.register('xencat-owl-carousel', 'BCxencat.owl_slider');
	XF.Element.register('xencat-infinite', 'BCxencat.Infinite');
	//XF.Element.register('porta-features', 'BCxencat.Features');
}
(window.jQuery, window, document);