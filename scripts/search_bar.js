(function () {
    const sform = document.getElementById('search-form');
    const sbtn = document.getElementById('search-button');
    const sbar = document.getElementById('search-bar');

    let previous_main;

    function update_main(main, t = true) {
        getHtmlContent('/includes/search.php', 'search=' + sbar.value).then((res) => {
            // change main's content

            if (t)
                transition(main, res);
            else
                main.innerHTML = res;

            setTimeout(function () {
                main.classList.remove('fade');
            }, 150);
        });
    }

    function transition(main, new_content) {
        main.classList.add('fade');
        setTimeout(function () {
            main.innerHTML = new_content;
            main.classList.add('visible');

            setTimeout(function () {
                main.classList.remove('fade');
                main.classList.remove('visible');
            }, 150);

        }, 150);
    }

    sbtn.addEventListener('click', function (e) {
        const main = document.getElementsByTagName('main')[0];
        const is_in_search = main.classList.contains('search-main');

        if (!is_in_search) {
            previous_main = main.innerHTML;
            main.classList.add('search-main');
            update_main(main);
        } else
            update_main(main, false);
    });

    sbar.addEventListener('keyup', function (e) {
        const main = document.getElementsByTagName('main')[0];
        const is_in_search = main.classList.contains('search-main');
        if (e.key.length === 1 || e.key == 'Backspace' && is_in_search) {
            if (sbar.value != '')
                if (!is_in_search) {
                    previous_main = main.innerHTML;
                    main.classList.add('search-main');
                    update_main(main);
                } else
                    update_main(main, false);
            else {
                main.classList.remove('search-main');
                transition(main, previous_main);
            }
        }
    });
})();