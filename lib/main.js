
/**
 * Your code there
 */


const dismissable = new Set();

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

document.querySelectorAll(".notifications .alert-dismissible").forEach(alert =>
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
});





/**
 * Put it last if overriding other styles
 */
import '../scss/main.scss';