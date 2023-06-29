<?php

declare(strict_types=1);

include 'page/header.php'; ?>

<div class="d-flex flex-column flex-lg-row">

   
    <div class="col-lg-4 p-3">
        <form action="./" method="post" class="w-100">

            <h4>Ajouter une tâche</h4>

            

            <div class="mb-3">
                <label for="name" class="form-label d-none">Nom de la tâche</label>
                <input 
                type="text" 
                class="form-control" 
                id="name" name="name" 
                placeholder="Nom de la tâche" 
                value="<?= $inputdata['name']        ?? ''; ?>"
                required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label d-none">Description</label>
                <input 
                type="text" 
                class="form-control" 
                id="description" name="description" 
                placeholder="Description de la tâche" 
                value="<?= $inputdata['description'] ?? ''; ?>"
                required>
            </div>


            <div class="mb-3">
                <label for="endDate" class="form-label d-none">Date limite</label>
                <input 
                type="datetime-local" 
                class="form-control" 
                id="end_date" 
                name="end_date" 
                placeholder="Date limite" 
                value="<?= $inputdata['end_date']    ?? ''; ?>"
                required>
            </div>

            <?php if($newRecord): ?>

                <div class="alert alert-success" role="alert">
                    Votre tâche à été ajoutée
                </div>

            <?php elseif($isRemoved): ?>

                <div class="alert alert-success" role="alert">
                    Votre tâche à été supprimée
                </div>
            <?php elseif($modRecord): ?>

                <div class="alert alert-success" role="alert">
                    Votre tâche à été modifiée
                </div>
            <?php elseif($error): ?>
                
                <div class="alert alert-danger" role="alert">
                    <?= $error; ?>
                </div>
            <?php endif; ?>

            <div class="d-flex justify-content-end align-items-center">
                <?php if( ! empty($inputdata)):?>
                    <input type="hidden" name="id" value="<?= $inputdata['id']; ?>">
                    <button type="submit" class="btn btn-outline-primary" name="action" value="edit">Modifier</button>


                <?php else: ?>
                    <button type="submit" class="btn btn-outline-primary" name="action" value="add">Ajouter</button>
                <?php endif; ?>
            </div>


            

        </form>
    </div>
    
    <div class="col-12 col-lg-8 p-3">


    <?php if(count($tasks)): ?>

        <table class="table table-striped w-100">
            <thead>
                <tr>
                 
                    <th class="col-4" colspan="2">
                        Tâche
                    </th>
                    <th class="col-4">
                        Description
                    </th>
                    <th class="col-2">
                        Date limite
                    </th>
                    <th class="col-2"></th>
                </tr>
            </thead>

            <tbody>
                <?php foreach($tasks as $task):
                    $done    = $task['done'];
                    $expired = ! $done && isExpired($task['end_date']);

                    ?>

                    <tr class="<?= $done ? 'table-success' : ''; ?><?= $expired ? 'table-danger" title="La tâche à expiré' : ''; ?>">
                        <td class="col-1">
                            
                            <form action="./" method="post">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" name="id" value="<?= $task['id']; ?>">
                                <div class="form-check form-switch">
                                    <input 
                                    class="form-check-input" 
                                    type="checkbox" 
                                    role="switch" 
                                    name="done"
                                    <?= $done ? 'checked' : ''; ?>
                                    <?= $expired ? 'disabled' : ''; ?>
                                    onchange="this.form.submit()">
                                </div>
                            </form>
                        </td>
                        <td class="col-3"><?= $task['name']; ?></td>
                        <td class="col-4"><?= $task['description']; ?></td>
                        <td class="col-2">
                            <input 
                                type="datetime-local" 
                                class="form-control form-control-sm" 
                                id="end_date" 
                                value="<?= $task['end_date']; ?>"
                                disabled>
                        </td>

                        <td class="col-2 text-end">
                            <form method="post" action="./">
                                <input type="hidden" name="id" value="<?= $task['id']; ?>">
                                <button 
                                    type="submit" 
                                    name="action" 
                                    value="edit_entry" 
                                    title="Editer la tâche"
                                    class="btn btn-secondary btn-sm"
                                >✎</button>
                                
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
</div>
    
















<?php include 'page/footer.php'; ?>