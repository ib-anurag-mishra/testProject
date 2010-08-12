INSERT INTO variables(library_id,authentication_variable,authentication_response) SELECT id,library_authentication_variable,library_authentication_response FROM libraries WHERE library_authentication_method IN ('innovative_var_wo_pin','sip2_var');# 4 row(s) affected.

UPDATE variables SET created = NOW(),modified = NOW();