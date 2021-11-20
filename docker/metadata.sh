#!/bin/bash
ECS_TASK=`wget -qO- ${ECS_CONTAINER_METADATA_URI}`
CLUSTER_NAME=`echo ${ECS_TASK} | jq --raw-output '.Labels."com.amazonaws.ecs.cluster"'`
FAMILY_NAME=`echo ${ECS_TASK} | jq --raw-output '.Labels."com.amazonaws.ecs.task-definition-family"'`
CURRENT_TASK=`echo ${ECS_TASK} | jq --raw-output '.Labels."com.amazonaws.ecs.task-arn"'`
ECS_TASKS=`aws ecs list-tasks --cluster ${CLUSTER_NAME} --family ${FAMILY_NAME} --desired-status RUNNING`
FIRST_TASK=`echo ${ECS_TASKS} | jq --raw-output '.taskArns[0]'`

if [ $FIRST_TASK = $CURRENT_TASK ]
then
    echo "son iguales"
    exit 0
else
    echo "son diferentes"
    exit 1
fi

##____________________________________________________________
##LOCAL
# ECS_TASK=`wget -qO- ${ECS_CONTAINER_METADATA_URI_V4}`
# CLUSTER_NAME=`cat tasks_test | jq --raw-output '.Labels."com.amazonaws.ecs.cluster"'`
# CURRENT_TASK=`cat tasks_test | jq --raw-output '.Labels."com.amazonaws.ecs.task-arn"'`
# ECS_TASKS=`aws ecs list-tasks --cluster ${CLUSTER_NAME}`
# FIRST_TASK=`echo ${ECS_TASKS} | jq --raw-output '.taskArns[0]'`

# echo ${FIRST_TASK}
# echo ${CURRENT_TASK}

# if [ $FIRST_TASK = $CURRENT_TASK ]
# then
#     echo "son iguales"
#     exit 0
# else
#     echo "son diferentes"
#     exit 1
# fi
# [profile gys-role]
# role_arn = arn:aws:iam::359623600646:role/gys-rol
# source_profile = user1
