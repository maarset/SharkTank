 $(document).ready(function () {

		 $("#TeamIDselect").change(function () {

    var teamid = $("#TeamIDselect").val();
    //alert("http://localhost:8080/dashboard/PDOWork.php?TeamID="+ teamid);
    //window.open("http://localhost:8080/dashboard/PDOWork.php?TeamID=" + teamid);
    location.href = "ledger.php?TeamID=" + teamid;
});

$("#TeamIDselectProduct").change(function () {

    var teamid = $("#TeamIDselectProduct").val();
    //alert("http://localhost:8080/dashboard/PDOWork.php?TeamID="+ teamid);
    //window.open("http://localhost:8080/dashboard/PDOWork.php?TeamID=" + teamid);
    location.href = "product.php?TeamID=" + teamid;
});
	 
 	$(".ts-sidebar-menu li a").each(function () {
 		if ($(this).next().length > 0) {
 			$(this).addClass("parent");
 		};
 	})
 	var menux = $('.ts-sidebar-menu li a.parent');
 	$('<div class="more"><i class="fa fa-angle-down"></i></div>').insertBefore(menux);
 	$('.more').click(function () {
 		$(this).parent('li').toggleClass('open');
 	});
	$('.parent').click(function (e) {
		e.preventDefault();
 		$(this).parent('li').toggleClass('open');
 	});
 	$('.menu-btn').click(function () {
 		$('nav.ts-sidebar').toggleClass('menu-open');
 	});
	 
	 
	 $('#zctb').DataTable();
	 
	  
	 $("#input-43").fileinput({
		showPreview: false,
		allowedFileExtensions: ["zip", "rar", "gz", "tgz"],
		elErrorContainer: "#errorBlock43"
			// you can configure `msgErrorClass` and `msgInvalidFileExtension` as well
	});



//--------------------------------------Ledger1------------------------------------------------//

var minDate1, maxDate1;
 
					// Custom filtering function which will search data in column four between two values
					DataTable.ext.search.push(function (settings, data, dataIndex) {
					    var min1 = minDate1.val();
					    var max1 = maxDate1.val();
					    var date1 = new Date(data[3]);
					
					    if (
					        (min1 === null && max1 === null) ||
					        (min1 === null && date1 <= max1) ||
					        (min1 <= date1 && max1 === null) ||
					        (min1 <= date1 && date1 <= max1)
					    ) {
					        return true;
					    }
					    return false;
					});

					// Create date inputs
					minDate1 = new DateTime('#min1', {
					    format: 'MMMM Do YYYY'
					});
					maxDate1 = new DateTime('#max1', {
					    format: 'MMMM Do YYYY'
					});

					// DataTables initialisation
					 new DataTable('#Ledger1', {
					    info: false,
					    ordering: true,
					    paging: true,
						dom: 'lBfrtip',
						 order: [[3, 'asc']],
						lengthMenu: [10, 25, 50, -1],
						buttons: [{
						     extend: 'pdf',
						     title: 'Customized PDF Title',
						     filename: 'customized_pdf_file_name'
						   }, {
						     extend: 'excel',
						     title: 'Customized EXCEL Title',
						     filename: 'customized_excel_file_name'
						   }, {
						     extend: 'csv',
						     filename: 'customized_csv_file_name' 
						   }],
//
					"oLanguage": {
					      "sSearch": "Filter Data"
					    },
					    //"iDisplayLength": -1,
						//"iDisplayLength": 10, //HAL HAL HAL HAL
					    "sPaginationType": "full_numbers",
					
						footerCallback: function (row, data, start, end, display) {
					        let api = this.api();
						
					        // Remove the formatting to get integer data for summation
					        let intVal = function (i) {
					            return typeof i === 'string'
					                ? i.replace(/[\$,]/g, '') * 1
					                : typeof i === 'number'
					                ? i
					                : 0;
					        };
						
					        // Total over all pages
					        total = api
					            .column(2)
					            .data()
					            .reduce((a, b) => intVal(a) + intVal(b), 0);
						
					        // Total over this page
					        pageTotal = api
					            .column(2, { page: 'current' })
					            .data()
					            .reduce((a, b) => intVal(a) + intVal(b), 0);
						
					        // Update footer
					        api.column(2).footer().innerHTML =
					          //  '$' + pageTotal + ' ( $' + total + ' total)';
							
							//	 '$' + Math.round(pageTotal * 100) / 100; // would out put 2.2
							 //   '$' + pageTotal ;
							 parseFloat(parseFloat(pageTotal).toFixed(2));
					    }
					
					});
					var table = $('#Ledger1').DataTable();

					// Refilter the table
					$('#min1, #max1').on('change', function () {
					//	alert("DRAWING TABLE");
					  //  table.draw();
					   $('#Ledger1').DataTable().draw();
					});





 });
