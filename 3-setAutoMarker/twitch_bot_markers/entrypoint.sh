while :;do
	echo ${user_id};
	if false;then
		:
	elif [ -z "${user_id}" ];then
		echo 'Missing user_id';
	elif [ -z "${client_id}" ];then
		echo 'Missing client_id';
	elif [ -z "${access_code}" ];then
		echo 'Missing access_code';
	else
		if [ $(date +%M) == '00' -o $(date +%S) == '00' ]; then
			curl \
				-s \
				-X POST \
				-H "Authorization: Bearer ${access_code}" \
				-H "Client-Id: ${client_id}" \
				-H 'Content-type: application/json' \
				-d "$(jq -n --arg desc "$(TZ='Asia/Tokyo' date -R)" --arg user "${user_id}" '{user_id: $user, description: $desc}')" \
				'https://api.twitch.tv/helix/streams/markers' | jq;
		fi;
	fi
	sleep 1;
done
