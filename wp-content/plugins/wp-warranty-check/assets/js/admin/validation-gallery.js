(function ($) {
  function addItem($preview, url) {
    if (!url) return;
    if ($preview.find('.trust-gallery-item[data-url="' + url.replace(/"/g, '\\"') + '"]').length) {
      return;
    }

    var $item = $(
      '<div class="trust-gallery-item" data-url="">' +
        '<img alt="">' +
        '<button type="button" class="button-link trust-gallery-remove" aria-label="حذف">&times;</button>' +
        '<input type="hidden" name="gallery_urls[]" value="">' +
      '</div>'
    );
    $item.attr('data-url', url);
    $item.find('img').attr('src', url);
    $item.find('input').val(url);
    $preview.append($item);
  }

  $(document).on('click', '[data-trust-gallery-add]', function (e) {
    e.preventDefault();
    var $field = $(this).closest('[data-trust-gallery]');
    var $preview = $field.find('[data-trust-gallery-preview]');

    var frame = wp.media({
      title: 'انتخاب تصاویر بارنامه',
      button: { text: 'افزودن تصاویر' },
      multiple: true,
      library: { type: 'image' }
    });

    frame.on('select', function () {
      frame.state().get('selection').each(function (attachment) {
        var data = attachment.toJSON();
        var url = (data.sizes && data.sizes.large && data.sizes.large.url) || data.url;
        addItem($preview, url);
      });
    });

    frame.open();
  });

  $(document).on('click', '.trust-gallery-remove', function (e) {
    e.preventDefault();
    $(this).closest('.trust-gallery-item').remove();
  });
})(jQuery);
