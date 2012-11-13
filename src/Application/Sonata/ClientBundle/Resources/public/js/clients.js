function addClient(client, cls){
    var columns = [
        '<a href="/sonata/client/client/'+client.id+'/edit">'+client.code_client+'</a>',
        client.raison_sociale,
        client.nature_du_client.name,
        '<a href="/sonata/user/user/'+client.user.id+'/edit">'+(client.user.firstname||client.user.lastname?($.trim(client.user.firstname+' '+client.user.lastname)):client.user.username)+'</a>',
        client.center_des_impots.nom,
        client.date_de_depot_id,
        '<img alt="'+(client.teledeclaration?'oui':'non')+'" src="/bundles/sonataadmin/famfamfam/'+(client.teledeclaration?'accept':'exclamation')+'.png">',
        '',
        '',
        '',
        '',
        formatDate(new Date(client.date_debut_mission.date.replace(/-/g, ' ')), default_date_format_js),
        formatDate(new Date(client.date_fin_mission.date.replace(/-/g, ' ')), default_date_format_js)
    ];
    var $table = $('.sonata-ba-list .table:first');
    var $tr = $table.find('tr:last').clone().addClass(cls);

    $tr.find('td').each(function(index){
        var $this = $(this);
        $this.attr('objectid', client.id);
        $this.html(columns[index]);
    });

    $table.find('tbody:first').append($tr);
}