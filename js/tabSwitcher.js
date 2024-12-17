window.onload = () => {
    // general elements
    const tabSwitchers = document.querySelectorAll('[data-switcher]');

    for (let i = 0; i < tabSwitchers.length; i++) {
        // specific element
        const tabSwitcher = tabSwitchers[i];
        const page_id = tabSwitcher.dataset.tab; // get the data-tabs value

        // onclick event
        tabSwitcher.addEventListener('click', () => {
            // remove active
            document.querySelector('.navbar-items .navbar-pills.active').classList.remove('active');
            // add class to the selected element
            tabSwitcher.classList.add('active');

            // callback function
            switchPage(page_id);
        })
    }
}
function switchPage(page_id) {
    const currentPage = document.querySelector('.pages .page.active');
    currentPage.classList.remove('active');

    const nextPage = document.querySelector(`.pages .page[data-page="${page_id}"]`);
    nextPage.classList.add('active');
}