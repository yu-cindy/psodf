// Call the dataTables jQuery plugin
/*$(document).ready(function() {
  $('#dataTable').DataTable();
});*/

$(document).ready(function(){
  $('#dataTable').DataTable({
  pageLength: 10,
  order: [],
  responsive: true,
  oLanguage: {
      "sProcessing": "處理中...",
      "sLengthMenu": "顯示 _MENU_ 項結果",
      "sZeroRecords": "沒有匹配結果",
      "sInfo": "顯示第 _START_ 至 _END_ 項結果，共 _TOTAL_ 項",
      "sInfoEmpty": "顯示第 0 至 0 項結果，共 0 項",
      "sInfoFiltered": "(從 _MAX_ 項結果過濾)",
      "sSearch": "搜尋:",
      "oPaginate": {
          "sFirst": "首頁",
          "sPrevious": "上頁",
          "sNext": "下頁",
          "sLast": "尾頁"
      }
  },
  destroy:true

});


});