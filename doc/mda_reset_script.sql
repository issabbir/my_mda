------------Script--------------------------
delete BERTHING_SCHEDULE where id <> 1 ;
delete JETTY where id <> 1 ;
delete PILOTAGE_VESSEL_CONDITIONS where id <> 1 ;
delete COLLECTION_SLIPS where id <> 1 ;
delete PILOTAGE_TUGS where id <> 1 ;
delete PILOTAGES where id <> 1 ;
delete MOORING_VISITS where id <> 1 ;

commit;




