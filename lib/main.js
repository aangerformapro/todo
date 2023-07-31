import { createElement } from '../modules/utils.mjs';
import '../scss/main.scss';
import { Api } from './api/api';

/**
 * Your code there
 */


const dismissable = new Set();

const
    notifArea = document.querySelector(".notifications"),
    tbody = document.querySelector('tbody');

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


// AJAX


function addTasks(tasks)
{


    tasks.forEach(task =>
    {

        const tr = createElement('tr',
            [
                createElement('<td class="col-1"/>', [
                    createElement('<form action="./" method="post" class="d-flex align-items-center py-1"/>', {
                        onsubmit(e)
                        {
                            e.preventDefault();
                        }
                    },
                        [
                            '<input type="hidden" name="action" value="update">',
                            '<input type="hidden" name="id" value="' + task.id + '">'
                                `<div class="form-check form-switch">
                                <input class="form-check-input" aria-label="Valide la tâche" type="checkbox" role="switch" name="done" disabled="" onchange="this.form.submit()">
                            </div>`
                        ]
                    )
                ]),
                `<td class="col-3"><div class="d-flex align-items-center py-1">${task.name}</div></td>`,
                `<td class="col-4"><div class="d-flex align-items-center py-1">${task.description}</div></td>`,
                `<td class="col-2"><div class="d-flex align-items-center py-1">
                    <input aria-label="Exécuter avant" type="datetime-local" class="form-control form-control-sm" name="end_date" value="${task.end_date}" disabled="">
                </div></td>`,
                createElement(`<td class="col-2 text-end">
                    <div class="d-flex align-items-center py-1">
                        <form method="post" action="./">
                            <input type="hidden" name="id" value="${task.id}">
                            <button type="submit" name="action" value="edit_entry" title="Editer la tâche" class="btn btn-secondary btn-sm">✎</button>
                            <button type="submit" name="action" value="delete" title="Supprimer la tâche" class="btn btn-danger btn-sm">×</button>
                        </form>
                    </div>
                </td>`,
                    {
                        onsubmit(e)
                        {
                            e.preventDefault();
                        },
                        onclick(e)
                        {
                            const btn = e.target.closest('button');
                            if (btn)
                            {
                                e.preventDefault();
                                const form = btn.closest("form");


                            }
                        }
                    }
                )
            ]
        );

        tbody.appendChild(

        );






    });



}



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



