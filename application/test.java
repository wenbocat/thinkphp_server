 public static void main(String[] args) {
    // 假设参数和方法的定义
    String anchorId = "your_anchor_id"; // 替换为实际的主播ID
    List<String> audienceIds = getLiveAudience(anchorId);

    // 假设从Request中获取NewMemberList
    List<Member> newMemberList = new ArrayList<>();
    // 假设在循环中获取NewMemberList并添加到newMemberList

    for (Member member : newMemberList) {
        if (!audienceIds.contains(member.getMemberAccount())) {
            audienceIds.add(member.getMemberAccount());
        }
    }

    setLiveAudience(anchorId, audienceIds);
}

public List<String> getLiveAudience(String anchorId) {
    String key = "LiveAudience_" + anchorId;
    String value = redisTemplate.opsForValue().get(key);

    if (value != null) {
        return Arrays.asList(value.split(","));
    }

    return new ArrayList<>();
}

public void setLiveAudience(String anchorId, List<String> audienceIds) {
    String key = "LiveAudience_" + anchorId;
    String value = String.join(",", audienceIds);

    redisTemplate.opsForValue().set(key, value);
}