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
        $('#global_search_form').attr('action', 'search').submit();
    }
});