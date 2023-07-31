/* global unsafeWindow, globalThis */



const IS_UNSAFE = typeof unsafeWindow !== 'undefined';
    IS_UNSAFE ? unsafeWindow : globalThis ?? window;

/**
 * Your code there
 */


const dismissable = new Set();

document.querySelector(".notifications");
    document.querySelector('tbody');

function dismissAlert(alert)
{

    if (dismissable.has(alert))
    {
        return;
    }

    dismissable.add(alert);
    alert.classList.add('dismiss');
    setTimeout(() =>
    {
        alert.remove();
    }, 800);
}



function setDismissable(alert)
{
    alert.addEventListener('click', ({ target }) =>
    {
        if (target.closest('.btn-close'))
        {
            dismissAlert(alert);
        }
    });

    setTimeout(() =>
    {

        if (alert.parentElement)
        {
            dismissAlert(alert);

        }

    }, 2000);
}

document.querySelectorAll(".notifications .alert-dismissible").forEach(alert =>
{
    setDismissable(alert);
});



// document.querySelector('form.form-add').addEventListener('submit', e =>
// {
//     e.preventDefault();

//     const
//         form = e.target.closest("form"),
//         params = Object.fromEntries((new FormData(form)).entries());


//     let
//         now = (new Date()).getTime(),
//         end_date = (new Date(params.end_date)).getTime(),
//         valid = now < end_date;
//     if (form.checkValidity() && valid)
//     {
//         Api.addTask(params).then(resp =>
//         {

//             if (resp.result === 'ok')
//             {



//             }
//         });

//     }





// });
//# sourceMappingURL=main.js.map
