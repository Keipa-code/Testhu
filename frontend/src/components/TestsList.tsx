import React, {FC} from 'react';
import {IPagination, ITest} from "../types/types";
import {Card, Pagination, Table} from "react-bootstrap";

interface TestsListProps {
    tests: ITest[];
    tableInfo? : string;
    pagination?: IPagination;
}

const TestsList: FC<TestsListProps> = ({tests, tableInfo, pagination}) => {
    const items = [];
    if (pagination) {
        const active = pagination["@id"];

        for (let number = 1; number <= 5; number++) {
            items.push(
                <Pagination.Item key={number} active={number === parseInt(active[active.length - 1])}>
                    {number}
                </Pagination.Item>,
            );
        }
    }

    return (
        <div>
        <Table>
            <thead style={{ display: tableInfo }}>
            <tr>
                <th>Название</th>
                <th>сдало / прошло</th>
            </tr>
            </thead>
            <tbody>
            {tests.map(test =>
                <tr key={test.id}>
                    <td>
                        <Card style={{ width: '18rem', border: 0 }}>
                            <Card.Body>
                                <Card.Title>{test.testName}</Card.Title>
                                {test.tags.map(tag =>
                                    <Card.Link key={tag.id} href="#">{tag.tagName}</Card.Link>
                                )}
                                </Card.Body>
                        </Card>
                    </td>
                    <td>
                        {test.passed} / {test.done}
                    </td>
                    <td>
                        <a href='#'>&#9658;</a>
                    </td>
                </tr>
            )}
            </tbody>
        </Table>

        <Pagination hidden={!pagination}>
            {items}
        </Pagination>
        </div>
    );
};

export default TestsList;