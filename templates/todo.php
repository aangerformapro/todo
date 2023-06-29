<?php

declare(strict_types=1);

include 'page/header.php'; ?>

<div class="container">

    <div class="p-5 my-4 bg-body-tertiary rounded-3">
        <div class="container-fluid py-5">
            <form action="./" method="post">

                <h4>Ajouter une tâche</h4>

                <?php if($newRecord): ?>

                    <div class="alert alert-success" role="alert">
                        Votre tâche à été ajoutée
                    </div>

                <?php elseif($isRemoved): ?>

                <?php elseif($error): ?>
                    <div class="alert alert-danger" role="alert">
                        <?= $error; ?>
                    </div>

                
                <?php endif; ?>

                <div class="mb-3">
                    <label for="name" class="form-label">Nom de la tâche</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Nom de la tâche" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <input type="text" class="form-control" id="description" name="description" placeholder="Description de la tâche" required>
                </div>


                <div class="mb-3">
                    <label for="endDate" class="form-label">Date limite</label>
                    <input type="datetime-local" class="form-control" id="end_date" name="end_date" placeholder="Date limite" required>
                </div>

                <div class="d-flex justify-content-end align-items-center">
                    <button type="submit" class="btn btn-outline-primary" name="action" value="add">Ajouter</button>
                </div>

            </form>
        </div>
    </div>

    <?php if(count($tasks)): ?>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="col-1">
                        
                    </th>
                    <th class="col-3">
                        Tâche
                    </th>
                    <th class="col-4">
                        Description
                    </th>
                    <th class="col-3">
                        Date limite
                    </th>
                    <th class="col-1"></th>
                </tr>
            </thead>

            <tbody>
                <?php foreach($tasks as $task):
                    $expired = isExpired($task['end_date']);
                    ?>

                    <tr class="<?= $expired ? 'table-danger' : ''; ?>">
                        <td class="col-1">
                            <?php if( ! $task['done']):?>
                                <form action="./" method="post">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" name="id" value="<?= $task['id']; ?>">
                                <div class="form-check form-switch">
                                    <input 
                                    class="form-check-input" 
                                    type="checkbox" 
                                    role="switch" 
                                    name="done"
                                    onchange="this.form.submit()">
                                </div>
                                </form>
                            <?php else: ?>
                                <div class="form-check form-switch">

                                    <input 
                                        class="form-check-input" 
                                        type="checkbox" 
                                        role="switch" 
                                        name="done"
                                        checked disabled>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td class="col-3"><?= $task['name']; ?></td>
                        <td class="col-4"><?= $task['description']; ?></td>
                        <td class="col-3">
                            <input 
                                type="datetime-local" 
                                class="form-control form-control-sm" 
                                id="end_date" 
                                value="<?= $task['end_date']; ?>"
                                disabled>
                        </td>

                        <td class="col-1 text-end">
                            <form method="post" action="./">
                                <imput type="hidden" name="id" value="<?= $task['id']; ?>">
                                <button 
                                    type="submit" 
                                    name="action" 
                                    value="delete" 
                                    title="Supprimer la tâche"
                                    class="btn btn-danger btn-sm"
                                >&times;</button>
                            </form>
                        </td>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php endif; ?>

</div>
    
















<?php include 'page/footer.php'; ?>