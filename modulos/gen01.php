<link href="css/sysgen_resumo.css" rel="stylesheet" type="text/css" />
<?php
/**
 * SysGen - Gerador de sistemas com Formdin Framework
 * https://github.com/bjverde/sysgen
 */
defined('APLICATIVO') or die();

$frm = new TForm('Gerador',500,700);
$frm->setFlat(true);
$frm->setMaximize(true);
$frm->addGroupField('gpx1','Requisito do PHP');
	$html = $frm->addHtmlField('conf','');
	$html->add('<br><b>Extensões PHP necessárias para o correto funcionamento:</b><br>');
	$validoPDOAndDBMS = TestConfigHelper::validatePDOAndDBMS($_SESSION[APLICATIVO]['DBMS']['TYPE'],$html);
$frm->closeGroup();

if($validoPDOAndDBMS){
	$frm->addGroupField('gpx2','Configurações de Banco');
	$pc = $frm->addPageControl('pc');
	
		$showAba = TestConfigHelper::showAbaDBMS($_SESSION[APLICATIVO]['DBMS']['TYPE'], DBMS_MYSQL);
		$pc->addPage(DBMS_MYSQL,$showAba,$showAba,'abamy');
			$frm->addHiddenField('myDbType',DBMS_MYSQL);
			$frm->addTextField('myHost'	,'Host:',20,true,20,'127.0.0.0.1',true,null,null,true);
			$frm->addTextField('myDb	','Database:',20,true,20,'test',true,null,null,true);
			$frm->addTextField('myUser'	,'User:',40,true,20,'root',false,null,null,true);
			$frm->addTextField('myPass'	,'Password:',40,true,20,'',false,null,null,true);
			$frm->addTextField('myPort'	,'Porta:',6,false,6,'3306',false,null,null,true,false);
			$frm->addButton('Testar Conexão',null,'btnTestarmy','testarConexao("my")',null,true,false);
			$frm->addHtmlField('myGride'	,'');

		$showAba = TestConfigHelper::showAbaDBMS($_SESSION[APLICATIVO]['DBMS']['TYPE'], DBMS_SQLITE);
		$pc->addPage(DBMS_SQLITE,$showAba,$showAba,'abaSqlite');
			$frm->addHiddenField('sqDbType',DBMS_SQLITE);
			$frm->addTextField('sqDb	','Database:',80,true,80,'bancos_locais/bdApoio.s3db',false,null,null,true);
			$frm->addButton('Testar Conexão',null,'btnTestarsq','testarConexao("sq")',null,true,false);
			$frm->addHtmlField('sqGride'	,'');
			
		
		$showAba = TestConfigHelper::showAbaDBMS($_SESSION[APLICATIVO]['DBMS']['TYPE'], DBMS_SQLSERVER);
		$pc->addPage(DBMS_SQLSERVER,$showAba,$showAba,'abass');								
			$frm->addHiddenField('ssDbType',DBMS_SQLSERVER);
			$frm->addTextField('ssHost'    ,'Host:',50,true,50,'127.0.0.0.1',true,null,null,true);
			$frm->addTextField('ssDb'      ,'Database:',20,true,20,'Northwind',true,null,null,true);
			$frm->addTextField('ssUser'    ,'User:',40,true,20,'sa',false,null,null,true);
			$frm->addTextField('ssPass'	   ,'Password:',40,true,20,'123456',false,null,null,true);
			$frm->addTextField('ssPort'    ,'Porta:',6,false,6,'1433',false,null,null,true,false);
			$frm->addButton('Testar Conexão',null,'btnTestarss','testarConexao("ss")',null,true,false);
			$frm->addHtmlField('ssGride'	,'');
			
			
		$showAba = TestConfigHelper::showAbaDBMS($_SESSION[APLICATIVO]['DBMS']['TYPE'], DBMS_POSTGRES);
		$pc->addPage(DBMS_POSTGRES,$showAba,$showAba,'abapg');
			$frm->addHiddenField('pgDbType',DBMS_POSTGRES);
			$frm->addTextField('pgHost','Host:',20,true,20,'127.0.0.0.1',true,null,null,true);
			$frm->addTextField('pgDb	','Database:',20,true,20,'test',true,null,null,true);
			$frm->addTextField('pgUser','User:',40,true,20,'postgres',false,null,null,true);
			$frm->addTextField('pgPass','Password:',40,true,20,'123456',false,null,null,true);
			$frm->addTextField('pgSchema','Esquema:',20,true,20,'public',false,null,null,true);
			$frm->addTextField('pgPort','Porta:',6,false,6,'5432',false,null,null,true,false);
			$frm->addButton('Testar Conexão',null,'btnTestarPg','testarConexao("pg")',null,true,false);	
			$frm->addHtmlField('pgGride','');


		$showAba = TestConfigHelper::showAbaDBMS($_SESSION[APLICATIVO]['DBMS']['TYPE'], DBMS_ORACLE);
		$pc->addPage(DBMS_ORACLE,$showAba,$showAba,'abaora');
			$frm->addHiddenField('oraDbType',DBMS_ORACLE);
			$frm->addTextField('oraHost'	,'Host:',50,true,50,'127.0.0.0.1',true,null,null,true);
			$frm->addTextField('oraDb'		,'Database:',20,true,20,'xe',true,null,null,true);
			$frm->addTextField('oraUser'	,'User:',40,true,20,'root',false,null,null,true);
			$frm->addTextField('oraPass'	,'Password:',40,true,20,'123456',false,null,null,true);
			$frm->addButton('Testar Conexão',null,'btnTestarora','testarConexao("ora")',null,true,false);
			$frm->addHtmlField('oraGride'	,'');
	
	$frm->closeGroup(); //Fechando Abas
	$frm->closeGroup(); //Close Group
		
}

$frm->addButton('Voltar', null, 'Voltar', null, null, true, false);
$frm->addButton('Limpar', null, 'Limpar', null, null, false, false);
$frm->addButton('Gerar estrutura', 'Gerar', 'Gerar', null, null, false, false);


$banco    = PostHelper::get('banco');
$dbType   = PostHelper::get('dbType');
$user     = PostHelper::get($banco.'User');
$password = PostHelper::get($banco.'Pass');
$dataBase = PostHelper::get($banco.'Db');
$host     = PostHelper::get($banco.'Host');
$port     = PostHelper::get($banco.'Port');
$schema   = PostHelper::get($banco.'Schema');
$sql      = PostHelper::get($banco.'Sql');

$acao = isset($acao) ? $acao : null;
switch( $acao ) {
	case 'testar_conexao':
		//prepareReturnAjax(0,null, $banco.print_r($_POST,TRUE) );
		if( $banco == 'my' || $banco == 'pg' || $banco == 'ora'|| $banco == 'ss'){
			if( ! $user ){
				prepareReturnAjax(0,null,'Informe o Usuário');
			}
		}
		$dao = new TDAO(null,$dbType,$user,$password,$dataBase,$host,$port,$schema);
		if( $dao->connect() ) {			
			$_SESSION[APLICATIVO]['DBMS']['USER'] = $user;
			$_SESSION[APLICATIVO]['DBMS']['PASSWORD'] = $password;
			$_SESSION[APLICATIVO]['DBMS']['DATABASE'] = $dataBase;
			$_SESSION[APLICATIVO]['DBMS']['HOST'] = $host;
			$_SESSION[APLICATIVO]['DBMS']['PORT'] = $port;
			$_SESSION[APLICATIVO]['DBMS']['SCHEMA'] = $schema;
			prepareReturnAjax(2,null,'Conexão OK! Pode clicar em "Gerar"');
		}else{
			prepareReturnAjax(0,null, $dao->getError());
		}		
	break;
	//--------------------------------------------------------------------------------
	case 'Voltar':
		$frm->redirect('gen00.php','Redirect realizado com sucesso.',true);
	break;
	//--------------------------------------------------------------------------------
	case 'Limpar':
		$frm->clearFields();
	break;
	//--------------------------------------------------------------------------------
	case 'Gerar':
		$frm->redirect('gen02.php','Redirect realizado com sucesso.',true);
	break;
}

$frm->show();
?>
<script>
function testarConexao(banco) {
	var data={};
	data['banco'] = banco;
	data['dbType'] = jQuery("#"+banco+'DbType').val();
	data[banco+'Host'] = jQuery("#"+banco+'Host').val();
	data[banco+'User'] = jQuery("#"+banco+'User').val();
	data[banco+'Pass'] = jQuery("#"+banco+'Pass').val();
	data[banco+'Port'] = jQuery("#"+banco+'Port').val();
	data[banco+'Db']   = jQuery("#"+banco+'Db').val();
	data[banco+'Schema']   = jQuery("#"+banco+'Schema').val();
	jQuery("#"+banco+"Gride").html('');
	fwAjaxRequest({
		"action":"testar_conexao",
		"async":false,
		"dataType":"json",
		"data":data,
		"callback":function(res)
		{
			if( res.status == 2 )
			{
				fwAlert( res.message);

			}
			else
			{
				jQuery("#"+banco+"Gride").html( res.message );
			}
		}
	})

}
</script>