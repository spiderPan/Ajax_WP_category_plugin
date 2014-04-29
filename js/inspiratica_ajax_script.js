/**
 * Created with JetBrains PhpStorm.
 * User: pan
 * Date: 2/28/14
 * Time: 4:02 PM
 * To change this template use File | Settings | File Templates.
 */
jQuery(document).ready(function ($) {

		$('#inspi_ajax_loader_form').on('change', function () {
			var _checked = $(".inspi_ajax_checkbox:checked").map(function () {
				return $(this).val();
			}).get();
			document.cookie = "checkedID=" + _checked;

			test(0);

		});
		test();

		function getCookie(cname) {
			var name = cname + "=";
			var ca = document.cookie.split(';');
			for (var i = 0; i < ca.length; i++) {
				var c = ca[i].trim();
				if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
			}
			return "";
		}

		function setCookie(cname, cvalue, exdays) {
			var d = new Date();
			d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
			var expires = "expires=" + d.toGMTString();
			document.cookie = cname + "=" + cvalue + "; " + expires;
		}

		function test(_pageDown) {

			var _allPost = $('.inspi-post-target');
			var _filter_prefix = 'inspi-cat-';
			var _selectedItem = $(".selected");
			var checked = $(".inspi_ajax_checkbox:checked");
			var _checked_label = $(".inspi_ajax_checkbox:checked +label");

			var searchIDs = $(checked).map(function () {
				return $(this).val();
			}).get();

			$(_selectedItem).removeClass('selected');
			$(_checked_label).addClass('selected');
			if (searchIDs.length > 0) {
				$(_allPost).hide();
				for (var i = 0; i < searchIDs.length; i++) {
					var _filter_class = _filter_prefix + searchIDs[i];
					var _filted_post = $(_allPost).filter("." + _filter_class);
					if ($(_filted_post).is(':hidden')) {
						$(_filted_post).show();
					}
				}
			} else {
				$(_allPost).show();
			}
			if (typeof(_pageDown) != 'undefined') {
				pagination(_pageDown);
			} else if (getCookie('pageVisited').length > 0) {
				var _page = parseInt(getCookie('pageVisited'));
				pagination(_page);
				//setCookie('pageVisited', '', 0.01);
			} else {
				pagination(0);
			}

		}

		$('.post-nav-item').live('click', function (e) {
			e.preventDefault();
			if (!$(this).hasClass('current-post-page')) {
				var _page = parseInt($(this).attr('href').slice(-1));
				console.log(_page);
				test(_page);
			}

		});

		function pagination(_visibleDown) {
			var _visibleAllPost = $('.inspi-post-target:visible');
			var _postsNav = $('.post-nav-wrapper');
			if (_visibleAllPost.length >= 5) {

				var _visibleDown = ((parseInt(_visibleDown) - 1 < 0) ? 0 : (parseInt(_visibleDown) - 1)  );

				var _visibleUp = _visibleDown + 1;
				console.log(_visibleDown);
				_postsNav.html('');
				var _visibleNum = _visibleAllPost.length;
				for (var i = 0; i <= _visibleNum; i++) {

					if ((i + 1) % 5 == 0 || (i == _visibleNum && _visibleNum % 5 != 0)) {
						var _page = Math.ceil(i / 5);
						var _html = '<a href="#page' + _page + '" class="post-nav-item ' + (_page == _visibleUp ? 'current-post-page' : '') + '">' + _page + '</a>';
						_postsNav.append(_html);
					}
					if (i < _visibleUp * 5 && i >= _visibleDown * 5) {
						$(_visibleAllPost[i]).show();
					} else {
						$(_visibleAllPost[i]).hide();
					}

				}

			} else {
				_postsNav.html('');
				var _html = '<a href="#page1" class="post-nav-item current-post-page">1</a>';
				_postsNav.append(_html);
			}

			document.cookie = 'pageVisited=' + (_visibleDown + 1);
		}

	}

)
;

