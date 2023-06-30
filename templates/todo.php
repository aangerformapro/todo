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
                name="end_date" 
                placeholder="Date limite" 
                value="<?= $inputdata['end_date']    ?? ''; ?>"
                required>
            </div>

            
            <div class="d-flex justify-content-end align-items-center">
                <?php if( ! empty($inputdata)):?>
                    <input type="hidden" name="id" value="<?= $inputdata['id']; ?>">
                    <button type="submit" class="btn btn-outline-primary" name="action" value="edit">Modifier</button>


                <?php else: ?>
                    <button type="submit" class="btn btn-outline-primary" name="action" value="add">Ajouter</button>
                <?php endif; ?>
            </div>

            <div class="notifications my-3">
                <?php if($newRecord): ?>

                    <div class="alert alert-success alert-dismissible" role="alert">
                        Votre tâche à été ajoutée
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>

                <?php elseif($isRemoved): ?>

                    <div class="alert alert-success alert-dismissible" role="alert">
                        Votre tâche à été supprimée
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php elseif($modRecord): ?>

                    <div class="alert alert-success alert-dismissible" role="alert">
                        Votre tâche à été modifiée
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php elseif($error): ?>
                    
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <?= $error; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
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
                           
                            <form action="./" method="post" class="d-flex align-items-center py-1">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" name="id" value="<?= $task['id']; ?>">
                                <div class="form-check form-switch">
                                    <input 
                                    class="form-check-input" 
                                    aria-label="Valide la tâche"
                                    type="checkbox" 
                                    role="switch" 
                                    name="done"
                                    <?= $done ? 'checked' : ''; ?>
                                    <?= $expired ? 'disabled' : ''; ?>
                                    onchange="this.form.submit()">
                                </div>
                            </form>
                          
                        </td>
                        <td class="col-3">
                            <div class="d-flex align-items-center py-1">
                                <?= $task['name']; ?>
                            </div>
                        </td>
                        <td class="col-4">
                            <div class="d-flex align-items-center py-1">
                                <?= $task['description']; ?>
                            </div>
                        </td>
                        <td class="col-2">
                            <div class="d-flex align-items-center py-1">
                                <input 
                                    aria-label="Exécuter avant"
                                    type="datetime-local" 
                                    class="form-control form-control-sm" 
                                    name="end_date" 
                                    value="<?= $task['end_date']; ?>"
                                    disabled>
                            </div>
                        </td>

                        <td class="col-2 text-end">
                            <div class="d-flex align-items-center py-1">
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
                            </div>
                        </td>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php endif; ?>
    </div>
</div>
    
















<?php include 'page/footer.php'; ?>