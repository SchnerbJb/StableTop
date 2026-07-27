import { Stack } from "expo-router";

export default function RootLayout() {
  return (
    <Stack
      screenOptions={{
        headerStyle: {
          backgroundColor: "#fr522e",
        },
        headerTintColor: "#fff",
        headerTitleStyle: {
          fontWeight: 'bold',
        },
      }}
    />
  );
}
