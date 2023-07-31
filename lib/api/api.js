

const ENDPOINT = './api.php';


function getURL(url)
{
    getURL.a ??= document.createElement("a");
    getURL.a.href = url;
    return new URL(getURL.a.href);
}


async function buildRequest({ action, method, params })
{

    action ??= 'all';
    method ??= 'GET';
    params ??= {};

    let init = {};
    const url = getURL(ENDPOINT);

    url.searchParams.set('action', action);

    if ('GET' === method)
    {
        for (let prop in params)
        {
            url.searchParams.set(prop, params[prop]);
        }
    }

    else
    {
        const body = new URLSearchParams(params);
        init = {
            method,
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body
        };

    }


    return await fetch(url, init).then(
        resp => resp.json(),
    );
}


export class Api
{


    static getAll()
    {
        return buildRequest({
            action: 'all',
        });
    }

    static getLast(id)
    {

        return buildRequest({
            action: 'last',
            params: {
                id
            }
        });

    }

    static addTask({
        name, description, end_date
    })
    {

        return buildRequest({
            action: "add",
            method: 'POST',
            params: { name, description, end_date },
        });
    }


    static removeTask({
        id
    })
    {
        return buildRequest({
            method: 'POST',
            action: 'remove',
            params: { id }
        });
    }


    static modifyTask({
        id, name, description, end_date
    })
    {
        return buildRequest({
            method: 'POST',
            action: 'update',
            params: { id, name, description, end_date },
        });

    }

}