<?php
require_once __DIR__.'/functions.php';
$id=(int)($_GET['id']??0); $t=get_ticket($id);
if(!$t){ header('Location: index.php'); exit; }

$errors=[];
if($_SERVER['REQUEST_METHOD']==='POST'){
  $title=trim($_POST['title']??''); $description=trim($_POST['description']??'');
  $category=$_POST['category']??'IT'; $priority=$_POST['priority']??'Moyenne';
  if($title===''){ $errors[]="Le titre est obligatoire."; }
  else { update_ticket($id,$title,$description,$category,$priority); header('Location: index.php'); exit; }
}
?>
<!doctype html><html lang="fr"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Modifier ticket #<?=$t['id']?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head><body class="bg-light"><div class="container py-4">
  <h1 class="mb-3">Modifier le ticket #<?=$t['id']?></h1>
  <?php if($errors): ?><div class="alert alert-danger"><?=implode('<br>',$errors)?></div><?php endif; ?>
  <form method="post" class="card card-body">
    <div class="mb-3">
      <label class="form-label">Titre *</label>
      <input class="form-control" name="title" required value="<?=htmlspecialchars($t['title'])?>">
    </div>
    <div class="mb-3">
      <label class="form-label">Description</label>
      <textarea class="form-control" name="description" rows="4"><?=htmlspecialchars($t['description'])?></textarea>
    </div>
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Catégorie</label>
        <select class="form-select" name="category">
          <?php foreach(['IT','RH','Administratif','Autre'] as $c): ?>
            <option value="<?=$c?>" <?=$t['category']===$c?'selected':''?>><?=$c?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label">Priorité</label>
        <select class="form-select" name="priority">
          <?php foreach(['Basse','Moyenne','Haute'] as $p): ?>
            <option value="<?=$p?>" <?=$t['priority']===$p?'selected':''?>><?=$p?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
    <div class="mt-3 d-flex gap-2">
      <button class="btn btn-primary">Enregistrer</button>
      <a class="btn btn-secondary" href="index.php">Annuler</a>
    </div>
  </form>
</div></body></html>
