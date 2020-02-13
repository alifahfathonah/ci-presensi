
<!-- <div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>User List</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title" id="txt">
            </div>
        </div>
    </div>
</div> -->

<div class="content mt-3">
  <?php if($message != null){ ?>
    <div class="alert alert-success" role="alert" id="infoMessage" >
      <?php echo $message;?>
    </div>
  <?php } ?>
  <div class="card">
    <div class="card-header">
      <h5 class="card-title display-4" style="font-size: 25px">Data Pengguna</h5>
    </div>
    <div class="card-body">
      <p>
        <?php echo anchor('auth/create_user', lang('index_create_user_link'), array('class'=>'btn btn-sm btn-outline-primary'))?>
        <?php echo anchor('auth/create_group', lang('index_create_group_link'), array('class'=>'btn btn-sm btn-outline-success'))?>
      </p>
      <table id="bootstrap-data-table-export" class="table table-sm table-striped table-bordered">
        <thead>
          <tr>
            <th>Username</th>
            <th><?php echo lang('index_fname_th');?></th>
            <th><?php echo lang('index_lname_th');?></th>
            <th><?php echo lang('index_email_th');?></th>
            <th><?php echo lang('index_groups_th');?></th>
            <th><?php echo lang('index_status_th');?></th>
            <th><?php echo lang('index_action_th');?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $user):?>
            <tr>
                    <td><?php echo htmlspecialchars($user->username,ENT_QUOTES,'UTF-8');?></td>
                    <td><?php echo htmlspecialchars($user->first_name,ENT_QUOTES,'UTF-8');?></td>
                    <td><?php echo htmlspecialchars($user->last_name,ENT_QUOTES,'UTF-8');?></td>
                    <td><?php echo htmlspecialchars($user->email,ENT_QUOTES,'UTF-8');?></td>
              <td>
                <?php foreach ($user->groups as $group):?>
                  <?php echo anchor("auth/edit_group/".$group->id, htmlspecialchars($group->name,ENT_QUOTES,'UTF-8')) ;?><br />
                        <?php endforeach?>
              </td>
              <td><?php echo ($user->active) ? anchor("auth/deactivate/".$user->id, lang('index_active_link')) : anchor("auth/activate/". $user->id, lang('index_inactive_link'));?></td>
              <td><?php
                    echo anchor("auth/edit_user/".$user->id, '<i class="fa fa-edit"></i>') ;
                    echo "&nbsp";
                    echo anchor("auth/delete_user/".$user->id, '<i class="fa fa-trash"></i>') ;
                  ?></td>
            </tr>
          <?php endforeach;?>
        </tbody>
      </table>
    </div>
  </div>
</div>
