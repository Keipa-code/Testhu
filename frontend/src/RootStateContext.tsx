import {NewTestStore} from "./pages/NewTest/NewTestStore";
import React, {createContext, FC, useContext} from "react";

type RootStateContextValue = {
    newTestStore: NewTestStore
}

const RootStateContext = createContext<RootStateContextValue>({} as RootStateContextValue)

const newTestStore = new NewTestStore()

export const RootStateProvider: FC<React.PropsWithChildren<{}>> = ({
    children,
}) => {
    return (
        <RootStateContext.Provider value={{ newTestStore }}>
            {children}
        </RootStateContext.Provider>
    )
}

export const useRootStore = () => useContext(RootStateContext)