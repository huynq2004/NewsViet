use newsviet;
CREATE LOGIN reader_login WITH PASSWORD = '123456';
CREATE USER reader_user FOR LOGIN reader_login;
CREATE LOGIN author_login WITH PASSWORD = '123456';
CREATE USER author_user FOR LOGIN author_login;
CREATE LOGIN admin_login WITH PASSWORD = '123456';
CREATE USER admin_user FOR LOGIN admin_login;

CREATE ROLE reader_role;
GRANT SELECT ON articles TO reader_role;
GRANT SELECT ON comments TO reader_role;
GRANT SELECT ON categories TO reader_role;

CREATE ROLE author_role;
GRANT SELECT, INSERT, UPDATE, DELETE ON articles TO author_role;
GRANT SELECT, INSERT, UPDATE, DELETE ON comments TO author_role;
GRANT SELECT ON categories TO author_role;
GRANT SELECT ON tags TO author_role;
GRANT SELECT, INSERT, UPDATE, DELETE ON article_tag TO author_role;

CREATE ROLE admin_role;
GRANT SELECT, INSERT, UPDATE, DELETE ON articles TO admin_role;
GRANT SELECT, INSERT, UPDATE, DELETE ON comments TO admin_role;
GRANT SELECT, INSERT, UPDATE, DELETE ON categories TO admin_role;
GRANT SELECT, INSERT, UPDATE, DELETE ON tags TO admin_role;
GRANT SELECT, INSERT, UPDATE, DELETE ON users TO admin_role;
GRANT SELECT, INSERT, UPDATE, DELETE ON article_tag TO admin_role;

EXEC sp_addrolemember 'reader_role', 'reader_user';
EXEC sp_addrolemember 'author_role', 'author_user';
EXEC sp_addrolemember 'admin_role', 'admin_user';
