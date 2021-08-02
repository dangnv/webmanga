$(document).on('change', '#change-chapter', function () {
    window.location.href = $(this).val();
})

function previous () {
    let currentKey = $('#change-chapter option[selected]').data('key');
    let previous = currentKey + 1;
    if ($('#option-chapter-'+previous).length) {
        window.location.href = $('#option-chapter-'+previous).attr('value');
    }
}

function next () {
    let currentKey = $('#change-chapter option[selected]').data('key');
    let next = currentKey - 1;
    if ($('#option-chapter-'+next).length) {
        window.location.href = $('#option-chapter-'+next).attr('value');
    }
}
