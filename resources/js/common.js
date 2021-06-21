// トップへ戻る
const PageTopBtn = document.getElementById('js-scroll-top');
PageTopBtn.addEventListener('click', () => {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
});

$('#global_search_btn').on('click', function() {
    if($('#global_search_freeword').val() === "") {
        if($('#global_search_category').val() === "") {
            location.href = "/";
        } else {
            location.href = "/" + $('#global_search_category').val();
        }
    } else {
        $('#global_search_form').submit();
    }
});

$('#sort').on('change', function() {
    const url = new URL(location);
    let sort_query = $(this).val();
    url.searchParams.set("sort", sort_query);

    if (url.searchParams.has('page')) {
        url.searchParams.delete('page');
    }

    location.href = url;
});

$('#sort_history').on('change', function() {
    const url = new URL(location);
    let sort_query = $(this).val();
    url.searchParams.set("sort_history", sort_query);

    location.href = url;
});