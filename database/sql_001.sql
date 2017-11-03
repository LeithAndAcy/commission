use commission;
update commission_contact_detail set delivery_money = delivery_money*2 where (contact_id = 'contact_001' OR contact_id ='contact_002');