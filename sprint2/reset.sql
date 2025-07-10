DELETE l FROM likes AS l, evqust AS e WHERE e.id = l.id_evqust AND e.id_event = ;
DELETE r FROM review AS r, evqust AS e WHERE e.id = r.id_evqust AND e.id_event = ;
DELETE q FROM questions AS q, evqust AS e WHERE q.id = e.id_question AND e.id_event =  AND q.readOnly = 0;
DELETE e FROM evqust AS e WHERE e.id_event = ;