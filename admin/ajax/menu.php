<?php
    
	include('../../include/config.php');
    include('../../include/database.php');
    include('../../include/functions.php');
?>
{
    "items": [
        {
            "title": "Principal",
            "iconCls": "server",
            "items": [
                {"text": "Página Principal", "id": "menuHome", "iconCls": "server", "menuURL": "<?php echo SITE_BASE_URL; ?>"},
                {"text": "Logout", "id": "menuLogout", "iconCls": "server", "menuURL": "<?php echo SITE_BASE_URL."logout" ?>"}
            ]
        },
        {
            "title": "Menu Investimentos",
            "iconCls": "workplace",
            "items": [
                {"text": "Bancos", "id": "menuBank", "iconCls": "workplace", "menuURL": "<?php echo SITE_BASE_URL."admin/bank" ?>"},
                {"text": "Agências", "id": "menuBankAgency", "iconCls": "workplace", "menuURL": "<?php echo SITE_BASE_URL."admin/bankagency" ?>"},
                {"text": "Setores", "id": "menuSector", "iconCls": "workplace", "menuURL": "<?php echo SITE_BASE_URL."admin/sector" ?>"},
                {"text": "SubSetores", "id": "menuSubsector", "iconCls": "workplace", "menuURL": "<?php echo SITE_BASE_URL."admin/subsector" ?>"},
                {"text": "Símbolos", "id": "menuSymbol", "iconCls": "workplace", "menuURL": "<?php echo SITE_BASE_URL."admin/symbol" ?>"},
            ]
        }
    ]
}