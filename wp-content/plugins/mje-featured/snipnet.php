posts_orderby
this->add_filter('posts_orderby', 'order_by_post_status', 10, 2);

order_by_post_status => relates to order in tax/category page. Dusavke this hook => run ok.

Sql: https://stackoverflow.com/questions/961007/how-do-i-use-row-number

https://stackoverflow.com/questions/29554817/i-have-an-issue-in-union-all-query-while-pagination-with-the-limit