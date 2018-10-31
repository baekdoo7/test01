DELIMITER $$
DROP PROCEDURE IF EXISTS ncpi.INSERT_FRAUD_REPORT_PROC$$

CREATE PROCEDURE ncpi.INSERT_FRAUD_REPORT_PROC(
    in IN_COM_IDX INT
  , in IN_REG_USER VARCHAR(45)
  , in IN_REG_DATE DATETIME
  , out RESULT INT
)
BEGIN

    -- 캠페인, 파트너, 설치시간
    DECLARE IN_CAM_IDX INT;
    DECLARE IN_PARTNER_IDX INT;
    DECLARE IN_INSTALL_TIME DATETIME;

    -- 각 가짜 조건에 해당하는 카운트 변수
    -- 조건 값 0: clean, 1: fraud
    DECLARE IN_CTIT_FRAUD_CNT INT default '0';
    DECLARE IN_INCORRECT_REGION_CNT INT default '0';
    DECLARE IN_IP_ADDRESS_DUPLICATION_CNT INT default '0';
    DECLARE IN_LANGUAGE_DISTRIBUTION_CNT INT default '0';
    DECLARE IN_ISP_DISTRIBUTION_CNT INT default '0';
    DECLARE IN_ADID_DUPLICATION_CNT INT default '0';
    DECLARE IN_CUSTOMER_USER_ID_DUPLICATION_CNT INT default '0';
    DECLARE IN_APP_VERSION_FRAUD_CNT INT default '0';
    DECLARE IN_WRONG_PLATFORM_CNT INT default '0';
    DECLARE IN_CLICK_INSTALL_IP_MISMATCH_CNT INT default '0';
    DECLARE IN_GOOGLE_CTIT_CNT INT default '0';

    -- Total, Clean, Fraud Install Cnt 변수
    DECLARE IN_TOTAL_INSTALL_CNT INT default '0';
    DECLARE IN_CLEAN_INSTALL_CNT INT default '0';
    DECLARE IN_FRAUD_INSTALL_CNT INT default '0';

    -- ERROR, EXCEPTION 변수(핸들러)
    DECLARE ERR INT default '0';
    DECLARE ERR_MESSAGE VARCHAR(500) default '';
    DECLARE NO_DATA BOOLEAN default FALSE;

    -- 광고주 별 Install Row 데이터 가져오기
    DECLARE INSTALL_DATA CURSOR FOR
     SELECT CAM_IDX
          , PARTNER_IDX
          , DATE_FORMAT(INSTALL_TIME, '%Y-%m-%d %H')
          , SUM(CTIT_FRAUD)
          , SUM(INCORRECT_REGION)
          , SUM(IP_ADDRESS_DUPLICATION)
          , SUM(LANGUAGE_DISTRIBUTION)
          , SUM(ISP_DISTRIBUTION)
          , SUM(ADID_DUPLICATION)
          , SUM(CUSTOMER_USER_ID_DUPLICATION)
          , SUM(APP_VERSION_FRAUD)
          , SUM(WRONG_PLATFORM)
          , SUM(CLICK_INSTALL_IP_MISMATCH)
          , SUM(GOOGLE_CTIT)
         -- , COUNT(INSTALL_IDX) AS TOTAL_INSTALL_CNT
          , SUM(IF(CTIT_FRAUD
            + INCORRECT_REGION
            + LANGUAGE_DISTRIBUTION
            + ISP_DISTRIBUTION
            + ADID_DUPLICATION
            + CUSTOMER_USER_ID_DUPLICATION
            + APP_VERSION_FRAUD
            + WRONG_PLATFORM
            + CLICK_INSTALL_IP_MISMATCH
            + GOOGLE_CTIT = 0, 1, 0)) AS CLEAN_INSTALL_CNT
          , SUM(IF(CTIT_FRAUD
            + INCORRECT_REGION
            + LANGUAGE_DISTRIBUTION
            + ISP_DISTRIBUTION
            + ADID_DUPLICATION
            + CUSTOMER_USER_ID_DUPLICATION
            + APP_VERSION_FRAUD
            + WRONG_PLATFORM
            + CLICK_INSTALL_IP_MISMATCH
            + GOOGLE_CTIT > 0, 1, 0)) AS FRAUD_INSTALL_CNT
       FROM ncpi.total_install_raw
      WHERE REG_DATE = IN_REG_DATE
        AND REG_USER = IN_REG_USER
      GROUP BY COM_IDX, CAM_IDX, PARTNER_IDX, DATE_FORMAT(REG_DATE, '%Y-%m-%d %H');

    -- 핸들러 선언
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET NO_DATA = TRUE;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET ERR = -1;

    OPEN INSTALL_DATA;

        read_Loop : LOOP
            FETCH INSTALL_DATA
             INTO IN_CAM_IDX
                , IN_PARTNER_IDX
                , IN_INSTALL_TIME
                , IN_CTIT_FRAUD_CNT
                , IN_INCORRECT_REGION_CNT
                , IN_IP_ADDRESS_DUPLICATION_CNT
                , IN_LANGUAGE_DISTRIBUTION_CNT
                , IN_ISP_DISTRIBUTION_CNT
                , IN_ADID_DUPLICATION_CNT
                , IN_CUSTOMER_USER_ID_DUPLICATION_CNT
                , IN_APP_VERSION_FRAUD_CNT
                , IN_WRONG_PLATFORM_CNT
                , IN_CLICK_INSTALL_IP_MISMATCH_CNT
                , IN_GOOGLE_CTIT_CNT
               -- , IN_TOTAL_INSTALL_CNT
                , IN_CLEAN_INSTALL_CNT
                , IN_FRAUD_INSTALL_CNT;

          --  SELECT NO_DATA;
            IF NO_DATA THEN INSERT INTO temp_err(err_content) VALUES('NO_DATA'); LEAVE read_Loop;
            END IF;

            IF ERR < 0 THEN INSERT INTO temp_err (bool) SELECT NO_DATA; LEAVE read_Loop;
            ELSE

                -- Report Table 데이터 삽입
                INSERT INTO fraud_report(
                            COM_IDX
                          , CAM_IDX
                          , PARTNER_IDX
                          , INSTALL_TIME
                          , CTIT_FRAUD
                          , INCORRECT_REGION
                          , IP_ADDRESS_DUPLICATION
                          , LANGUAGE_DISTRIBUTION
                          , ISP_DISTRIBUTION
                          , ADID_DUPLICATION
                          , CUSTOMER_USER_ID_DUPLICATION
                          , APP_VERSION_FRAUD
                          , WRONG_PLATFORM
                          , CLICK_INSTALL_IP_MISMATCH
                          , GOOGLE_CTIT
                          , CLEAN_INSTALL
                          , FRAUD_INSTALL
                          , REG_DATE
                          , REG_USER
                          ) VALUES (
                            IN_COM_IDX
                          , IN_CAM_IDX
                          , IN_PARTNER_IDX
                          , IN_INSTALL_TIME
                          , IN_CTIT_FRAUD_CNT
                          , IN_INCORRECT_REGION_CNT
                          , IN_IP_ADDRESS_DUPLICATION_CNT
                          , IN_LANGUAGE_DISTRIBUTION_CNT
                          , IN_ISP_DISTRIBUTION_CNT
                          , IN_ADID_DUPLICATION_CNT
                          , IN_CUSTOMER_USER_ID_DUPLICATION_CNT
                          , IN_APP_VERSION_FRAUD_CNT
                          , IN_WRONG_PLATFORM_CNT
                          , IN_CLICK_INSTALL_IP_MISMATCH_CNT
                          , IN_GOOGLE_CTIT_CNT
                          , IN_CLEAN_INSTALL_CNT
                          , IN_FRAUD_INSTALL_CNT
                          , IN_REG_DATE
                          , IN_REG_USER);

            END IF;

        END LOOP read_Loop;

    CLOSE INSTALL_DATA;

    IF ERR < 0 THEN INSERT INTO temp_err(err_content, err_msg) SELECT 'Last', ERR_MESSAGE; ROLLBACK;
    ELSE COMMIT;
    END IF;

END$$
채팅 종료
메시지를 입력하세요...

DECLARE exit handler for sqlexception
BEGIN
GET DIAGNOSTICS CONDITION 1
@p1 = RETURNED_SQLSTATE, @p2 = MESSAGE_TEXT;
SELECT @p1 as RETURNED_SQLSTATE  , @p2 as MESSAGE_TEXT;
ROLLBACK;
END;

adv_idx


area_idx
















