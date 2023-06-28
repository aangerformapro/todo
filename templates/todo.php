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
                <input type="date" class="form-control" id="endDate" name="endDate" placeholder="Date limite" required>
            </div>

            <div class="d-flex justify-content-end align-items-center">
                <button type="submit" class="btn btn-outline-primary">Ajouter</button>
            </div>

        </form>
    </div>
</div>



    
</div>















<?php include 'page/footer.php'; ?>