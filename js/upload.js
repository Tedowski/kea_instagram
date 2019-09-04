function filePreview(input) {
    if (input.files && input.files[0]) {
        let reader = new FileReader();
        reader.onload = function (e) {
            $('#uploadImg').html('');
            $('#uploadImg').append(`<img src="${e.target.result}"/>`);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

$("#fileToUpload").change(function () {
    filePreview(this);
});