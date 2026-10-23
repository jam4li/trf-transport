(function ($) {
	function isPdf(url) {
		return /\.pdf(\?|#|$)/i.test(url || "");
	}

	function fileName(url) {
		try {
			var path = String(url || "").split(/[?#]/)[0];
			var name = decodeURIComponent(path.substring(path.lastIndexOf("/") + 1));
			return name || "PDF";
		} catch (e) {
			return "PDF";
		}
	}

	function galleryName($field) {
		return $field.attr("data-trf-gallery-name") || "gallery_urls[]";
	}

	function addItem($preview, url, inputName) {
		if (!url) {
			return;
		}
		if ($preview.find('.trf-track-gallery-item[data-url="' + url.replace(/"/g, '\\"') + '"]').length) {
			return;
		}

		var $item;
		if (isPdf(url)) {
			$item = $(
				'<div class="trf-track-gallery-item is-file" data-url="">' +
					'<a class="trf-track-gallery-file" href="" target="_blank" rel="noopener noreferrer">' +
					'<span class="trf-track-gallery-file__badge">PDF</span>' +
					'<span class="trf-track-gallery-file__name"></span>' +
					"</a>" +
					'<button type="button" class="button-link trf-track-gallery-remove" aria-label="حذف">&times;</button>' +
					'<input type="hidden" name="" value="">' +
					"</div>"
			);
			$item.find("a").attr("href", url);
			$item.find(".trf-track-gallery-file__name").text(fileName(url));
		} else {
			$item = $(
				'<div class="trf-track-gallery-item" data-url="">' +
					'<img alt="">' +
					'<button type="button" class="button-link trf-track-gallery-remove" aria-label="حذف">&times;</button>' +
					'<input type="hidden" name="" value="">' +
					"</div>"
			);
			$item.find("img").attr("src", url);
		}

		$item.attr("data-url", url);
		$item.find("input[type='hidden']").attr("name", inputName).val(url);
		$preview.append($item);
	}

	function syncEntryRemoveState() {
		var $entries = $("[data-trf-entries] [data-trf-entry]");
		$entries.find("[data-trf-entry-remove]").prop("disabled", $entries.length < 2);
	}

	function initJalaliPicker() {
		if (typeof jalaliDatepicker === "undefined" || !jalaliDatepicker.startWatch) {
			return;
		}

		jalaliDatepicker.startWatch({
			time: false,
			hasSecond: false,
			hideAfterChange: true,
			useDropDownYears: false,
			persianDigits: false,
			zIndex: 100000,
		});
	}

	$(document).on("click", "[data-trf-gallery-add]", function (e) {
		e.preventDefault();
		var $field = $(this).closest("[data-trf-gallery]");
		var $preview = $field.find("[data-trf-gallery-preview]");
		var inputName = galleryName($field);

		var frame = wp.media({
			title: "انتخاب مدارک بارنامه",
			button: { text: "افزودن فایل‌ها" },
			multiple: true,
			library: { type: ["image", "application/pdf"] },
		});

		frame.on("select", function () {
			frame
				.state()
				.get("selection")
				.each(function (attachment) {
					var data = attachment.toJSON();
					var mime = data.mime || "";
					var allowedImage = data.type === "image" || /^image\//.test(mime);
					var allowedPdf = mime === "application/pdf" || isPdf(data.filename || data.url || "");
					if (!allowedImage && !allowedPdf) {
						return;
					}
					var url = (data.sizes && data.sizes.large && data.sizes.large.url) || data.url;
					addItem($preview, url, inputName);
				});
		});

		frame.open();
	});

	$(document).on("click", ".trf-track-gallery-remove", function (e) {
		e.preventDefault();
		$(this).closest(".trf-track-gallery-item").remove();
	});

	$(document).on("click", "[data-trf-entry-add]", function (e) {
		e.preventDefault();
		var template = document.getElementById("trf-track-entry-template");
		var $list = $("[data-trf-entries]");
		if (!template || !$list.length) {
			return;
		}

		var html = template.innerHTML.replace(/__INDEX__/g, String(Date.now()));
		var $row = $(html);
		$row.find("textarea, input.trf-track-jalali").attr("required", "required");
		$list.append($row);
		syncEntryRemoveState();
	});

	$(document).on("click", "[data-trf-entry-remove]", function (e) {
		e.preventDefault();
		var $entries = $("[data-trf-entries] [data-trf-entry]");
		if ($entries.length < 2) {
			return;
		}
		$(this).closest("[data-trf-entry]").remove();
		syncEntryRemoveState();
	});

	$(function () {
		syncEntryRemoveState();
		initJalaliPicker();
	});
})(jQuery);
