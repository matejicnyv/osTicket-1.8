<?php
if(!defined('OSTADMININC') || !$thisstaff || !$thisstaff->isAdmin()) die('Access Denied');
$info=$members=array();
$qstr='';
if ($team && $_REQUEST['a']!='add') {
    //Editing Team
    $title=__('Update Team');
    $action='update';
    $submit_text=__('Save Changes');
    $info=$team->getInfo();
    $info['id']=$team->getId();
    $trans['name'] = $team->getTranslateTag('name');
    $qstr.='&id='.$team->getId();
    $members = $team->getMembers();
} else {
    $title=__('Add New Team');
    $action='create';
    $submit_text=__('Create Team');
    $info['isenabled']=1;
    $info['noalerts']=0;
    $qstr.='&a='.$_REQUEST['a'];
}

$info = Format::htmlchars(($errors && $_POST) ? $_POST : $info);
?>
<form action="teams.php?<?php echo $qstr; ?>" method="post" id="save">
 <?php csrf_token(); ?>
 <input type="hidden" name="do" value="<?php echo $action; ?>">
 <input type="hidden" name="a" value="<?php echo Format::htmlchars($_REQUEST['a']); ?>">
 <input type="hidden" name="id" value="<?php echo $info['id']; ?>">
 <h2><?php echo __('Team');?>&nbsp;
    <i class="help-tip icon-question-sign" href="#teams"></i>
    </h2>
<br>
<ul class="tabs">
    <li class="active"><a href="#team">
        <i class="icon-file"></i> <?php echo __('Team'); ?></a></li>
    <?php
    if ($members) { ?>
    <li><a href="#members">
        <i class="icon-group"></i> <?php echo __('Members'); ?></a></li>
    <?php
    } ?>
</ul>
<div id="team" class="tab_content">
 <table class="form_table" width="940" border="0" cellspacing="0" cellpadding="2">
    <thead>
        <tr>
            <th colspan="2">
                <h4><?php echo $title; ?></h4>
                <em><strong><?php echo __('Team Information'); ?></strong>:</em>
            </th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td width="180" class="required">
                <?php echo __('Name');?>:
            </td>
            <td>
                <input type="text" size="30" name="name" value="<?php echo $info['name']; ?>"
                data-translate-tag="<?php echo $trans['name']; ?>"/>
                &nbsp;<span class="error">*&nbsp;<?php echo $errors['name']; ?></span>
            </td>
        </tr>
        <tr>
            <td width="180" class="required">
                <?php echo __('Status');?>:
            </td>
            <td>
                <span>
                <input type="radio" name="isenabled" value="1" <?php echo $info['isenabled']?'checked="checked"':''; ?>><strong><?php echo __('Active');?></strong>
                &nbsp;
                <input type="radio" name="isenabled" value="0" <?php echo !$info['isenabled']?'checked="checked"':''; ?>><?php echo __('Disabled');?>
                &nbsp;<span class="error">*&nbsp;</span>
                <i class="help-tip icon-question-sign" href="#status"></i>
                </span>
            </td>
        </tr>
        <tr>
            <td width="180">
                <?php echo __('Team Lead');?>:
            </td>
            <td>
                <span>
                <select name="lead_id">
                    <option value="0">&mdash; <?php echo __('None');?> &mdash;</option>
                    <?php
                    if ($members) {
                        foreach($members as $k=>$staff){
                            $selected=($info['lead_id'] && $staff->getId()==$info['lead_id'])?'selected="selected"':'';
                            echo sprintf('<option value="%d" %s>%s</option>',$staff->getId(),$selected,$staff->getName());
                        }
                    }
                    ?>
                </select>
                &nbsp;<span class="error"><?php echo $errors['lead_id']; ?></span>
                <i class="help-tip icon-question-sign" href="#lead"></i>
                </span>
            </td>
        </tr>
        <tr>
            <td width="180">
                <?php echo __('Assignment Alert');?>:
            </td>
            <td>
                <input type="checkbox" name="noalerts" value="1" <?php echo $info['noalerts']?'checked="checked"':''; ?> >
                <?php echo __('<strong>Disable</strong> for this Team'); ?>
                <i class="help-tip icon-question-sign" href="#assignment_alert"></i>
            </td>
        </tr>
        <tr>
            <th colspan="2">
                <em><strong><?php echo __('Admin Notes');?></strong>: <?php echo __('Internal notes viewable by all admins.');?>&nbsp;</em>
            </th>
        </tr>
        <tr>
            <td colspan=2>
                <textarea class="richtext no-bar" name="notes" cols="21"
                    rows="8" style="width: 80%;"><?php echo $info['notes']; ?></textarea>
            </td>
        </tr>
    </tbody>
</table>
</div>
<?php
if ($members) { ?>
<div id="members" class="tab_content" style="display:none">
   <table class="form_table" width="940" border="0" cellspacing="0" cellpadding="2">
    <thead>
        <tr>
            <th>
                <em><?php echo __('Agents who are members of this team'); ?><i
                    class="help-tip icon-question-sign" href="#members"></i></em>
            </th>
        </tr>
    </thead>
    <tbody>
    <?php
        foreach($members as $k=>$staff) {
            echo sprintf('<tr><td colspan=2><span style="width:350px;padding-left:5px; display:block;" class="pull-left">
                        <b><a href="staff.php?id=%d">%s</a></span></b>
                        &nbsp;<input type="checkbox" name="remove[]" value="%d"><i>'.__('Remove').'</i></td></tr>',
                        $staff->getId() ,
                        $staff->getName(),
                        $staff->getId());

        }
     ?>
    </tbody>
   </table>
</div>
<?php
} ?>
<p style="text-align:center">
    <input type="submit" name="submit" value="<?php echo $submit_text; ?>">
    <input type="reset"  name="reset"  value="<?php echo __('Reset');?>">
    <input type="button" name="cancel" value="<?php echo __('Cancel');?>" onclick='window.location.href="?"'>
</p>
</form>
