insert into blocks (name, description, status_block) 
values ('Bloco D', 'Bloco d', true),('Bloco B', 'bloco b', true);

insert into rooms (name, description, status_room, block_id, responsable_id, capacity) 
values ('A105', 'Sala A105', true, 1, 1, 20), ('A205', 'Sala a205', true, 1, 1, 25), ('A305', 'Sala A305', true, 1, 1, 25);

insert into ccr (name, status_ccr) 
values ('EDF', true),('MAT', true),('POR', true),('GEO', true);