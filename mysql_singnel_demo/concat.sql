-- CONCAT(str1,str2,…) 返回结果为连接参数产生的字符串。如有任何一个参数为NULL ，则返回值为 NULL。
SELECT CONCAT(11,22,33) number;   -- 112233
SELECT CONCAT(11,null,33) number; -- null


-- CONCAT(str1,str2,…) 返回结果为连接参数产生的字符串。如有任何一个参数为NULL ，则返回值为 NULL。
SELECT CONCAT(11,22,33) number;   -- 112233
SELECT CONCAT(11,null,33) number; -- null


-- CONCAT_WS(separator,str1,str2,...) CONCAT_WS() 代表 CONCAT With Separator ，是CONCAT()的特殊形式。
SELECT CONCAT_WS(',',11,22,33) str; -- 11,22,33
SELECT CONCAT_WS(',',11,null,33) str; -- 11,33 和MySQL中concat函数不同的是, concat_ws函数在执行的时候,不会因为NULL值而返回NULL
SELECT CONCAT_WS(NULL,11,22,33) str; -- null 如果分隔符为 NULL，则结果为 NULL。函数会忽略任何分隔符参数后的 NULL 值。