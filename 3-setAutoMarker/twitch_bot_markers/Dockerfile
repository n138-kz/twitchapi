FROM alpine

RUN apk add curl jq tzdata

COPY twitch_bot_markers/entrypoint.sh /
RUN chmod a+x /entrypoint.sh

ENTRYPOINT '/entrypoint.sh'