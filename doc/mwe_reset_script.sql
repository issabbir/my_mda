--Need to make view of tables
--===============================
--MW_VESSELS comes from craft management
--MW_PRODUCTS comes from craft inventory
--MW_UNITS comes from craft inventory


-----------------Scheduler-------------
-- php artisan Scheduler:auto




------------Script--------------------------
delete MW_INSPECTION_REQUISITION_DTLS where id <> 1 ;
delete MW_INSPECTION_REQUISITIONS where id <> 1 ;
delete MW_MAINTENANCE_REQ_HISTORY where id <> 1 ;
delete MW_MAINTENANCE_REQS where id <> 1 ;
delete MW_MAINTENANCE_SCHEDULE where id <> 1 ;
delete MW_NOTIFICATIONS where id <> 1 ;
delete MW_VESSEL_INSPECTIONS where id <> 1 ;

commit;



