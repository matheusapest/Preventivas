document.body.insertAdjacentHTML(
    'afterbegin',
    `
        <div
            style="
                position: fixed;
                top: 10px;
                left: 10px;
                right: 10px;
                z-index: 999999;
                padding: 15px;
                background: red;
                color: white;
                font-size: 18px;
                font-weight: bold;
                border-radius: 10px;
            "
        >
            MOBILE-MENU.JS EXECUTOU
        </div>
    `
);
