require('./bootstrap');

$(document).on('click', '.phone-button', function () {
    let button = $(this);
    axios.post(button.data('source')).then(function (response) {
        button.find('.number').html(response.data)
    }).catch(function (error) {
        console.error(error);
    });
});

$('.banner').each(function () {
    let banner = $(this);
    let url = banner.data('url');
    let format = banner.data('format');
    let category = banner.data('category');
    let region = banner.data('region');

    axios
        .get(url, {
            params: {
                format: format,
                category: category,
                region: region
            }
        })
        .then(function (response) {
            banner.html(response.data);
        })
        .catch(function (error) {
            console.log(error);
        });
});

$('.summernote').summernote({
    height: 300,
    callbacks: {
        onImageUpload: function (files) {
            let editor = $(this);
            let form = new FormData();

            form.append('file', files[0]);

            axios
                .post(editor.data('image-url'), form)
                .then(function (response) {
                    editor.summernote('insertImage', response.data);
                })
                .catch(function (error) {
                    console.error(error);
                });
        }
    }
});
