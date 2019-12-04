-- 以id分组，把name字段的值打印在一行，逗号分隔(默认)
SELECT id,GROUP_CONCAT(name) `name` FROM aa GROUP BY id;

-- 以id分组，把name字段的值打印在一行，分号分隔
SELECT id,GROUP_CONCAT(name SEPARATOR ';') `name` FROM aa GROUP BY id;

-- 以id分组，把去冗余的name字段的值打印在一行，逗号分隔
SELECT id,GROUP_CONCAT(DISTINCT name) name FROM aa GROUP BY id;

-- 以id分组，把name字段的值打印在一行，逗号分隔，以name排倒序
SELECT id,GROUP_CONCAT(name ORDER BY name DESC) `name` FROM aa GROUP BY id;

-- select REPEAT 用来复制字符串,如下'ab'表示要复制的字符串，2表示复制的份数
SELECT REPEAT('ab,',3) repeats;