const searchLabel = document.getElementById('searchLabel');
const searchForm = document.getElementById('searchForm');

searchLabel.addEventListener('click', function() { 
    searchForm.submit();
});