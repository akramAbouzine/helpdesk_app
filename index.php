<?php
require_once __DIR__.'/functions.php';

$errors=[];
if($_SERVER['REQUEST_METHOD']==='POST' && ($_POST['action']??'')==='create'){
  $title=trim($_POST['title']??''); $description=trim($_POST['description']??'');
  $category=$_POST['category']??'IT'; $priority=$_POST['priority']??'Moyenne';
  if($title===''){ $errors[]="Le titre est obligatoire."; }
  else { create_ticket($title,$description,$category,$priority); header('Location: index.php'); exit; }
}

$q=trim($_GET['q']??''); $cat=$_GET['category']??''; $prio=$_GET['priority']??''; $stat=$_GET['status']??'';
$tickets=list_tickets($q,$cat,$prio,$stat);
?>
<!doctype html><html lang="fr"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Helpdesk - Tickets</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head><body class="bg-light">
<div class="container py-4">
  <h1 class="mb-3">🎫 Helpdesk – Gestion des tickets</h1>

  <!-- Filtres / Recherche -->
  <form class="row g-2 mb-3" method="get">
    <div class="col-md-3"><input class="form-control" name="q" placeholder="Recherche titre..." value="<?=htmlspecialchars($q)?>"></div>
    <div class="col-md-2">
      <select class="form-select" name="category">
        <option value="">Catégorie</option>
        <?php foreach(['IT','RH','Administratif','Autre'] as $c): ?>
          <option value="<?=$c?>" <?=$cat===$c?'selected':''?>><?=$c?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-2">
      <select class="form-select" name="priority">
        <?php foreach([''=>'Priorité','Basse'=>'Basse','Moyenne'=>'Moyenne','Haute'=>'Haute'] as $k=>$v): ?>
          <option value="<?=$k?>" <?=$prio===$k?'selected':''?>><?=$v?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-2">
      <select class="form-select" name="status">
        <?php foreach([''=>'Statut','Ouvert'=>'Ouvert','Résolu'=>'Résolu'] as $k=>$v): ?>
          <option value="<?=$k?>" <?=$stat===$k?'selected':''?>><?=$v?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-2"><button class="btn btn-outline-primary w-100">Filtrer</button></div>
    <div class="col-md-1"><a class="btn btn-outline-secondary w-100" href="index.php">Reset</a></div>
  </form>

  <!-- Formulaire création -->
  <div class="card mb-4">
    <div class="card-header">Créer un ticket</div>
    <div class="card-body">
      <?php if($errors): ?><div class="alert alert-danger"><?=implode('<br>',$errors)?></div><?php endif; ?>
      <form method="post" class="row g-3">
        <input type="hidden" name="action" value="create">
        <div class="col-md-6">
          <label class="form-label">Titre *</label>
          <input class="form-control" name="title" required>
        </div>
        <div class="col-md-3">
          <label class="form-label">Catégorie</label>
          <select class="form-select" name="category">
            <?php foreach(['IT','RH','Administratif','Autre'] as $c): ?><option value="<?=$c?>"><?=$c?></option><?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">Priorité</label>
          <select class="form-select" name="priority">
            <?php foreach(['Basse','Moyenne','Haute'] as $p): ?><option value="<?=$p?>"><?=$p?></option><?php endforeach; ?>
          </select>
        </div>
        <div class="col-12">
          <label class="form-label">Description</label>
          <textarea class="form-control" name="description" rows="3"></textarea>
        </div>
        <div class="col-12"><button class="btn btn-primary">Créer</button></div>
      </form>
    </div>
  </div>

  <!-- Liste -->
  <div class="card">
    <div class="card-header">Tickets (<?=count($tickets)?>)</div>
    <div class="card-body p-0">
      <table class="table table-striped mb-0 align-middle">
        <thead><tr>
          <th>#</th><th>Titre</th><th>Catégorie</th><th>Priorité</th><th>Statut</th><th>Créé</th><th class="text-end">Actions</th>
        </tr></thead>
        <tbody>
        <?php if(!$tickets): ?>
          <tr><td colspan="7" class="text-center text-muted py-3">Aucun ticket</td></tr>
        <?php endif; ?>
        <?php foreach($tickets as $t): ?>
          <tr>
            <td><?=$t['id']?></td>
            <td><?=htmlspecialchars($t['title'])?></td>
            <td><?=$t['category']?></td>
            <td><?=$t['priority']?></td>
            <td>
              <span class="badge <?=$t['status']==='Ouvert'?'bg-secondary':'bg-success'?>"><?=$t['status']?></span>
            </td>
            <td><?=$t['created_at']?></td>
            <td class="text-end">
              <a class="btn btn-sm btn-outline-primary" href="edit.php?id=<?=$t['id']?>">Modifier</a>
              <form class="d-inline" method="post" action="toggle.php">
                <input type="hidden" name="id" value="<?=$t['id']?>">
                <button class="btn btn-sm btn-outline-warning"><?=$t['status']==='Ouvert'?'Marquer Résolu':'Rouvrir'?></button>
              </form>
              <form class="d-inline" method="post" action="delete.php" onsubmit="return confirm('Supprimer ce ticket ?');">
                <input type="hidden" name="id" value="<?=$t['id']?>">
                <button class="btn btn-sm btn-outline-danger">Supprimer</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</body></html>
