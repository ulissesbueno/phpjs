<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/r/bs-3.3.5/jq-2.1.4,dt-1.10.8/datatables.min.css"/>

<body>
	<div class="container">
		<table id="datagrid" class="table table-striped table-bordered" width="100%"></table>
	</div>
</body>

<script type="text/javascript" src='https://code.jquery.com/jquery-3.1.1.min.js'></script>
<script type="text/javascript" src="https://cdn.datatables.net/r/bs-3.3.5/jqc-1.11.3,dt-1.10.8/datatables.min.js"></script>

<script type="text/javascript" src='php.js?<?=uniqid()?>'></script>
<script type="text/javascript">
	
	var system = new $PHP('system');
	system.loaded = LoadGrid;

	function LoadGrid(){
		system.call('getCustomer',[],function( ret ){
			//console.log( Array.from(system.data) );
			$('#datagrid').DataTable( {
		        data: system.data,
		        columns: [
		            { title: "Name" }
		        ]
		    });
		})
	}

	
</script>


