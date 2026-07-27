import AsyncStorage from "@react-native-async-storage/async-storage";
import { useEffect, useState } from "react";
import { StyleSheet, Text, TextInput, TouchableOpacity, useColorScheme, View } from "react-native";
import DropDownPicker from "react-native-dropdown-picker";

type Transaction = {
  id: number,
  note: string,
  amount: number,
  label: string,
  category: string,
}

export default function App() {
  const [dropdownOpen, setDropdownOpen] = useState(false)
  const [totalAmount, setTotalAmount] = useState(0);
  const [nextAmount, setNextAmount] = useState(0);
  const [credit, setCredit] = useState(null);
  const [nextTransactionNote, setNextTransactionNote] = useState("");
  const [listTransaction, setListTransaction] = useState<Array<Transaction>>([]);
  const colorScheme = useColorScheme();
  const [items, setItems] = useState([
    { label: 'Credit', value: 1, containerStyle: [styles.containerLabel] },
    { label: 'Debit', value: -1, containerStyle: [styles.containerLabel] }
  ])
  const [labelOpen, setLabelOpen] = useState(false);
  const [label, setLabel] = useState(null);
  const [labelsList, setLabelsList] = useState([
    { label: 'Taxes', value: 'Taxes', containerStyle: [styles.containerLabel, styles.taxes] },
    { label: 'Groceries', value: 'Groceries', containerStyle: [styles.containerLabel, styles.groceries] },
    { label: 'Salary', value: 'Salary', containerStyle: [styles.containerLabel, styles.salary] },
    { label: 'Hobbies', value: 'Hobbies', containerStyle: [styles.containerLabel, styles.hobbies] },
    { label: 'Health', value: 'Health', containerStyle: [styles.containerLabel, styles.health] },
    { label: 'Education', value: 'Education', containerStyle: [styles.containerLabel, styles.education] },
    { label: 'Misc.', value: 'Misc', containerStyle: [styles.containerLabel, styles.misc] },
  ])

  const isDark = colorScheme === "dark";
  const theme = isDark ? darkTheme : lightTheme;

  useEffect(() => {
    const checkAmount = async () => {
      const total = await AsyncStorage.getItem("total");
      const jsonTotal = total != null ? JSON.parse(total) : null
      if (jsonTotal) {
        setTotalAmount(parseInt(jsonTotal.amount));
      } else {
        setTotalAmount(0);
      }
    }
    const changeList = async () => {
      const transactions = await AsyncStorage.getItem("transactions");
      const jsonTransactions = transactions != null ? JSON.parse(transactions) : null
      if (jsonTransactions) {
        console.log(jsonTransactions)
        setListTransaction(jsonTransactions)
      }
    }
    checkAmount()
    changeList()
  }, [])

  const modifyAmount = async (amount: number) => {
    let sum = Number(totalAmount) + Number(amount)
    const jsonValue = JSON.stringify({ amount: sum })
    await AsyncStorage.setItem("total", jsonValue)
    setTotalAmount(sum)
  }

  const modifyTransactionList = async (newList: Array<Transaction>) => {
    const jsonValue = JSON.stringify(newList)
    await AsyncStorage.setItem("transactions", jsonValue)
    setListTransaction(newList)
  }

  const addTransaction = async () => {
    if (label != null && nextAmount != 0 && credit != null) {
      let amount = Number(nextAmount) * Number(credit)
      modifyAmount(amount);
      modifyTransactionList([
        ...listTransaction,
        { id: listTransaction.length, note: nextTransactionNote, amount: amount, label: label, category: label }
      ])
      setLabel(null);
      setNextAmount(0);
      setNextTransactionNote("");
      setCredit(null);
    }
  }

  return (
    <View style={[styles.container, { backgroundColor: theme.background }]}>
      <Text style={[styles.title, { color: theme.text }]}>You have {totalAmount} euros</Text>
      <View style={styles.innerContainer}>

        <TextInput
          style={[
            styles.input,
            {
              backgroundColor: theme.inputBackground,
              color: theme.text,
              borderColor: theme.border,
            },
          ]}
          keyboardType="numeric"
          placeholder="Amount"
          placeholderTextColor={theme.placeholder}
          value={nextAmount}
          onChangeText={setNextAmount}
        />

        <View style={{ flexDirection: "row" }}>

          <DropDownPicker
            open={dropdownOpen}
            value={credit}
            items={items}
            setOpen={setDropdownOpen}
            setValue={setCredit}
            setItems={setItems}>
          </DropDownPicker>

          <DropDownPicker
            open={labelOpen}
            value={label}
            items={labelsList}
            setOpen={setLabelOpen}
            setValue={setLabel}
            setItems={setLabelsList}>
          </DropDownPicker>
        </View>

        <TextInput
          style={[
            styles.input,
            {
              backgroundColor: theme.inputBackground,
              color: theme.text,
              borderColor: theme.border,
            },
          ]}
          placeholder="Notes (Optionnal)"
          placeholderTextColor={theme.placeholder}
          value={nextTransactionNote}
          onChangeText={setNextTransactionNote}
        />

        <TouchableOpacity
          style={[styles.button, { backgroundColor: theme.primary, zIndex: -1 }]}
          onPress={addTransaction}
        >
          <Text style={styles.buttonText}>AddTransaction</Text>
        </TouchableOpacity>

        <View>
          {listTransaction.map(transaction => (
            <Text key={transaction.id}>{transaction.label} : {transaction.amount} {transaction.note}</Text>
          ))}
        </View>
      </View>
    </View>
  );
}

const lightTheme = {
  background: "#FFFFFF",
  text: "#222222",
  primary: "purple",
  inputBackground: "#F2F2F7",
  border: "#D1D1D6",
  placeholder: "#999999",
};

const darkTheme = {
  background: "#121212",
  text: "#FFFFFF",
  primary: "purple",
  inputBackground: "#1E1E1E",
  border: "#3A3A3C",
  placeholder: "#AAAAAA",
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    justifyContent: "center",
    alignItems: "center",
    padding: 24,
  },
  title: {
    fontSize: 28,
    fontWeight: "bold",
    marginBottom: 32,
  },
  innerContainer: {
    alignItems: "center",
    width: "100%",
  },
  input: {
    width: "50%",
    padding: 14,
    borderWidth: 1,
    borderRadius: 8,
    marginBottom: 16,
    fontSize: 16,
    alignItems: "center"
  },
  button: {
    width: "30%",
    padding: 14,
    borderRadius: 8,
    alignItems: "center",
  },
  buttonText: {
    color: "#FFFFFF",
    fontWeight: "600",
    fontSize: 16,
  },
  containerLabel: {
    padding: 4,
    borderColor: "rgb(255, 255, 255)",
    borderWidth: 1,
  },
  misc: {
    backgroundColor: "rgb(0, 77, 0)",
  },
  salary: {
    backgroundColor: "rgb(104, 6, 6)",
  },
  taxes: {
    backgroundColor: "rgb(112, 209, 151)",
  },
  groceries: {
    backgroundColor: "rgb(78, 123, 158)",
  },
  hobbies: {
    backgroundColor: "rgb(127, 7, 65)",
  },
  health: {
    backgroundColor: "rgb(175, 239, 0)",
  },
  education: {
    backgroundColor: "rgb(170, 129, 201)",
  },
});