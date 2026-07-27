import { useState } from "react";
import { Modal, Pressable, Text, View } from "react-native";

export default function Summary() {

    const [transactionPromptVisible, setTransactionPromptVisible] = useState(false);

    return (
        <View>
            <Modal
                visible={transactionPromptVisible}>
            </Modal>
            <Text>Hai</Text>
            <Pressable
                onPress={() => setTransactionPromptVisible(true)}>
                <Text>+</Text>
            </Pressable>
        </View>
    )
}