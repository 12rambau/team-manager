function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function (e) {
            var img = $('#imageFile');

              img
              .attr('src', e.target.result)
              .width(img.width())
              .height(img.height());
      };
      reader.readAsDataURL(input.files[0]);
      $('#previewAlert').attr('class', $('#previewAlert').attr('class').replace('sr-only', ''));
    }
}