<?php

// Setup the $row variable if it's not set but intended
if(isset($user)){
    $row = $user;
} elseif(isset($blog)){
    $row = $blog;
}

$availableRoles = $row->roles;
?>
@foreach($roles as $role)
    <div class="inline field">
        <div class="ui slider checkbox">
            <input
            <?php

                $checked = false;
                if(isset($row)){
                    if($availableRoles->contains(function ($item, $key) use ($role){
                        return $item->type == $role->type;
                    })) {
                        $checked = true;
                        echo "checked='checked' ";
                    }
                }

                $disabled = false;
                if($role->id == 1 || $role->id == 2 ) {
                    $disabled = true;
                    echo "disabled";
                }
            ?>
            type="checkbox" name="{{ $role->id }}" tabindex="0" class="hidden">

            @if($checked && $disabled )
                <input type="hidden" name="{{ $role->id }}" checked='checked' value="on">
            @endif

            <label>{{ $role->type }}
                @if($role->id == 1 || $role->id == 2 )
                    <i data-variation="wide" class="red lock icon pop" data-position="right center" data-title="{{ trans('laralum.unassignable_role') }}" data-content="This role can't be modified"></i>
                @endif
            </label>
        </div>
    </div>
@endforeach
