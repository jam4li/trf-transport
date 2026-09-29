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

	function addItem($preview, url) {
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
					'<input type="hidden" name="gallery_urls[]" value="">' +
					"</div>"
			);
			$item.find("a").attr("href", url);
			$item.find(".trf-track-gallery-file__name").text(fileName(url));
		} else {
			$item = $(
				'<div class="trf-track-gallery-item" data-url="">' +
					'<img alt="">' +
					'<button type="button" class="button-link trf-track-gallery-remove" aria-label="حذف">&times;</button>' +
					'<input type="hidden" name="gallery_urls[]" value="">' +
					"</div>"
			);
			$item.find("img").attr("src", url);
		}

		$item.attr("data-url", url);
		$item.find('input[name="gallery_urls[]"]').val(url);
		$preview.append($item);
	}

	$(document).on("click", "[data-trf-gallery-add]", function (e) {
		e.preventDefault();
		var $field = $(this).closest("[data-trf-gallery]");
		var $preview = $field.find("[data-trf-gallery-preview]");

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
					addItem($preview, url);
				});
		});

		frame.open();
	});

	$(document).on("click", ".trf-track-gallery-remove", function (e) {
		e.preventDefault();
		$(this).closest(".trf-track-gallery-item").remove();
	});
})(jQuery);
