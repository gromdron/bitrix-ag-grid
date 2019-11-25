<?
$aMenuLinks = Array(
	Array(
		"CRM", 
		"/crm/", 
		Array(), 
		Array(), 
		"CBXFeatures::IsFeatureEnabled('crm') && CModule::IncludeModule('crm') && CCrmPerms::IsAccessEnabled()" 
	),
    Array(
        "Таблица",
        "/sheet/",
        Array(),
        Array(),
        ""
    ),
    Array(
        "БП таблицы",
        "/sheet/bp_list.php",
        Array(),
        Array(),
        ""
    )
);
?>